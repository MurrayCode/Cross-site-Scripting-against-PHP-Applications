#!/usr/bin/perl
#
# vim: set number :
# vim: set tabstop=4 :
#
# This file is part of the Brim project.
# The brim project is located at the following location:
# http://www.brim-project.org
#
# Enjoy :-)
#
# Brim - Copyright (c) 2003 - 2006 Barry Nauta
#
# The Brim project is released under the General Public License
# More detailes in the file 'gpl.html' or on the following
# website: <code>http://www.gnu.org</code> and look for licenses
#
# author: Reflous
# copyright: Reflous

use strict;
use DBI;
use DBD::mysql;
use Net::POP3;

##################
# Global Variables

# System Setup
my $email_username = 'notes';
my $email_password = 'pw';
my $email_host = 'localhost';
my $db_dbname = 'brim';
my $db_username = 'root';
my $db_password = 'pw';
my $db_host = 'localhost';
my $brim_username = 'admin';

# regular expressions that deliniate what to do with the email
my %brim_type = (NOTE => qr/(?i)\[note\]\s*/,
BOOKMARK => qr/(?i)\[bookmark\]\s*/,
CONTACT => qr/(?i)\[contact\]\s*/,
TASK => qr/(?i)\[task\]\s*/,
);

# Database handler
my $dbh;

##################
# Main Program
my $emails = check_email();
if ($emails) {
		$dbh = DBI->connect("DBI:mysql:database=$db_dbname;host=$db_host",
				$db_username, $db_password,
				{'RaiseError' => 1})
				or die "Couldn't connect to brim";

		foreach my $email (@$emails) {
				if ($email->{subject} =~ s/$brim_type{'NOTE'}//) {
						insert_note($email);
				} elsif ($email->{subject} =~ s/$brim_type{'BOOKMARK'}//) {
						insert_bookmark($email);
				} elsif ($email->{subject} =~ s/$brim_type{'CONTACT'}//) {
						insert_contact($email);
				} elsif ($email->{subject} =~ s/$brim_type{'TASK'}//) {
						insert_task($email);
				}
		}
		# Disconnect
		$dbh->disconnect();

}
exit(0);

##################
# Functions

sub check_email {
		my (@brim_fields);

		my $pop = Net::POP3->new($email_host, Timeout => 60) or
		die "Couldn't connect to $email_host";

		if ($pop->login($email_username, $email_password) > 0) {
				my $msgnums = $pop->list; # hashref of msgnum => size
				foreach my $msgnum (keys %$msgnums) {
						my $msg = $pop->get($msgnum);
						my($subject, $body) = parse_email($msg);
						push(@brim_fields, {subject => $subject, body => $body});
						#$pop->delete($msgnum);
				}
		}
		$pop->quit;

		return \@brim_fields;
}

sub parse_email {
		my ($email) = @_;
		my $in_body = 0;
		my ($subject, $body);

		my @email = @$email;

		for (my $i = 0; $i <= $#email; $i++) {
				if ( (! $in_body) && ($email[$i] =~ /^\s*$/) ) {
						$in_body = 1;
						next;
				}

				if ( (! $in_body) && ($email[$i] =~ /^Subject: (.*)$/) ) {
						$subject = $1;
				} elsif ($in_body) {
						$body = join("\n", @email[$i .. $#email]);
						last;
				}
		}

		return($subject, $body);
}

sub insert_note {
		my ($note) = @_;

		my $sth = $dbh->prepare("INSERT INTO brim_notes (owner, parent_id, is_parent, name, description, visibility, when_created) VALUES ('$brim_username', 0, 0, ?, ?, 'private', sysdate())");
		$sth->bind_param(1, $note->{subject});
		$sth->bind_param(2, $note->{body});
		my $rv = $sth->execute;
		$sth->finish;

		return;
}

sub insert_bookmark {
		my ($bkmark) = @_;
		my ($link, $desc);

		if ($bkmark->{body} =~ /^(.*?)(http:\/\/\S+)(.*)$/si) {
				$link = $2;
				$desc = $1 . " " . $3;
		} else {
				warn ("Bookmark " . $bkmark->{body} . " not properly formatted will be destroyed");
				return;
		}

		my $sth = $dbh->prepare("INSERT INTO brim_bookmarks (owner, parent_id, is_parent, name, description, locator, visibility, when_created) VALUES ('$brim_username', 0, 0, ?, ?, ?, 'private', sysdate())");
		$sth->bind_param(1, $bkmark->{subject});
		$sth->bind_param(2, $desc);
		$sth->bind_param(3, $link);
		my $rv = $sth->execute;
		$sth->finish;

		return;
}

sub insert_contact {
		# currently unsupported, this would take a lot of codes and/or parsing
		# a bit of pain in the ass to write, I'll do it later
}

sub insert_task {
		my ($task) = @_;

		my $sth = $dbh->prepare("INSERT INTO brim_tasks (owner, parent_id, is_parent, name, description, visibility, when_created, priority, start_date, end_date, status, percent_complete) VALUES ('$brim_username', 0, 0, ?, ?, 'private', sysdate(), 3, sysdate(), sysdate(), 'Imported from Email', 0)");
		$sth->bind_param(1, $task->{subject});
		$sth->bind_param(2, $task->{body});
		my $rv = $sth->execute;
		$sth->finish;

		return;
}
