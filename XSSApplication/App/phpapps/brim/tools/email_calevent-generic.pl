#!/usr/bin/perl
#
# Handy script that will send out notices for EVERY brim calendar event
#
# 

use strict;
use DBI;
use DBD::mysql;
use Date::Calc qw ( Delta_DHMS Today_and_Now );
use Net::SMTP;

##################
# Global Variables

## System Setup
# Change these variables
my $mail_host = 'mail.localhost'; # smtp server
my $mail_from = 'calendar@localhost';
# For each account that should get event notification emails, insert a new
# key/value into the hash %brim_users
# format should be: username => 'email'
my %brim_users = (admin    => 'root@localhost',
                  username => 'email');

my $db_username = 'root';
my $db_password = 'pass';
my $db_host = 'localhost';
my $db_name = 'brim';
my $log_file = '/dir/to/log/email_calevent.log'; # this file must exist

my $hours_before_notice = 1;   # Hours before an event notice will be sent out
my $text_msg_enabled    = 1;   # Set to 0 if you aren't text messaging
my $text_msg_chars      = 160; # How many characters you can txt msg
my $priority_level      = 1;   # Min priority level the event must have to send notice

# Database handler
my $dbh;

# log info
my %log_hash;

# Test to make sure we can access a mail server, if not we don't want
# to execute any of the log writing
my $smtp = Net::SMTP->new("$mail_host");
unless ($smtp) {
  die "Couldn't connect to the SMTP server at $mail_host";
}

##################
# Main Program
$dbh = DBI->connect("DBI:mysql:database=$db_name;host=$db_host",
                    $db_username, $db_password,
                    {'RaiseError' => 1})
  or die "Couldn't connect to $db_name at $db_host: $!";
load_log(); # temporary until notices are handled in the database structure

my @users = get_all_users($dbh);

# loop through events for each user
foreach my $user (@users) {
  my $sth = $dbh->prepare("SELECT item_id, event_start_date, name, description, location, event_end_date
                           FROM brim_calendar_event 
                           WHERE event_start_date > sysdate()
                             AND owner = '$user'
                             AND priority <= $priority_level");
  $sth->execute();

  # Loop through all the events and check to see if they are ready for a notice
  # to be sent out
  while (my $ref = $sth->fetchrow_hashref()) {
    if (new_event($ref->{'item_id'}, $ref->{'event_start_date'}, $user)) {
      run_event($ref->{'item_id'}, $ref->{'event_start_date'}, $ref->{'event_end_date'}, $ref->{'name'}, $ref->{'description'}, $ref->{'location'}, $user);
    }
  }
}

# Disconnect
$dbh->disconnect();
$smtp->quit;

exit(0);

##################
# Functions

# The next two functions take care of logging to a text file
# 
# This is how email_calevent keeps track of what notices
# it has sent out.  This should be in the database, but
# currently there is no structure setup for it.
sub load_log {
  open (LOG, "<", $log_file) || 
    die "Couldn't open log_file at $log_file: $!";
  while (<LOG>) {
    chomp;
#    my @data = split /\t/, $_, -1;
    my ($logtime_pid, $user, $id, $event_date, $desc) = split /\t/, $_, -1;
    $log_hash{$user}{$id}++;
  }
  close (LOG);
}

sub log_event {
  my ($user, $id, $start_date, $name) = @_;
  my $now = localtime;

  open (LOG, ">>", $log_file) || 
    die "Couldn't open log_file at $log_file: $!";
  print LOG "[$now] [${$}]\t$user\t$id\t$start_date\t$name\n";
  close (LOG);
}


# Parse out the database dateformat for Date::Calc
sub db_dateparse {
  my $date = shift;
  my ($year,$month,$day, $hour,$min,$sec);

  if ($date =~ /^(\d{4})\-(\d+)\-(\d+) (\d+):(\d+):(\d{1,2})/) {
    ($year,$month,$day, $hour,$min,$sec) = ($1, $2, $3, $4, $5, $6);
  } else {
    warn ("Error parsing date date");
    return 0;
  }

  return ($year,$month,$day, $hour,$min,$sec);
}

# Function checks to see if it is time to send out notice 
# for any calendar events
sub new_event {
  my ($id, $start_date, $user) = @_;

  if (exists $log_hash{$user}{$id}) {
    return 0;
  }

  my ($year1,$month1,$day1, $hour1,$min1,$sec1) = Today_and_Now();
  my ($year2,$month2,$day2, $hour2,$min2,$sec2) = (db_dateparse($start_date));

  my ($D_d, $Dh,$Dm,$Ds) = Delta_DHMS($year1,$month1,$day1, $hour1,$min1,$sec1,
                                                  $year2,$month2,$day2, $hour2,$min2,$sec2);

  if ( ( ($D_d * 24) + $Dh) <= $hours_before_notice) {
    return 1;
  }
  
  return 0;
}

# Setup here whatever you want to happen when notice is suppose
# to be executed.  The program is currently just sending out an
# email.
sub run_event {
  my ($id, $start_date, $end_date, $name, $desc, $location, $user) = @_;
  my $fstart = join(":", (db_dateparse($start_date))[3..4]);
  my $fend = join(":", (db_dateparse($end_date))[3..4]);
  my $msg = "[" . $fstart  . "-" . $fend . "] $name - $desc \@ $location\n";

  # For sending text messages
  $msg = substr($msg, 0, ($text_msg_chars - 1)) if ($text_msg_enabled); 

#  send_email($mail_to, $msg);
  send_email($brim_users{$user}, $msg);

  log_event($user, $id, $start_date, $name);
}

# Send an email out
sub send_email {
  my ($to, $msg) = @_;

  $smtp->mail("$mail_from");
  $smtp->to("$to");
  
  $smtp->data();

  $smtp->datasend("To: $to\n");
  $smtp->datasend("From: $mail_from\n");
# Uncomment the line below if you want your message in the subject line
#  $smtp->datasend("Subject: $msg\n");
  $smtp->datasend("\n");
  $smtp->datasend("$msg\n");
  $smtp->dataend();
}

sub get_all_users {
  my $dbh = shift;
  my @users = ();

  # Pull out all the users
  my $sth = $dbh->prepare("SELECT loginname
                         FROM brim_users");
  $sth->execute();
  while (my $ref = $sth->fetchrow_hashref()) {
    my $login = $ref->{'loginname'};
    push (@users, $login) if (exists $brim_users{$login});
  }
  
  return @users;
}
