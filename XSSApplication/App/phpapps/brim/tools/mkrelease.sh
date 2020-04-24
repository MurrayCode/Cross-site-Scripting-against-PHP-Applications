#!/bin/bash
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
# Copyright (c) 2003 - 2006 Barry Nauta
#
# The Brim project is released under the General Public License
# More detailes in the file 'gpl.html' or on the following
# website: <code>http://www.gnu.org</code> and look for licenses
#

# defensive shell programming - treat unset variables as an error
# set -x
set -u

BASEDIR=`pwd`/..
TODAY=`date '+%d_%b_%Y'`;
PHPDOCU_HOME=$HOME/bin/phpdocu
PHPXREF_HOME=$HOME/bin/phpxref

backup ()
{
	cp -R $BASEDIR/../brim /tmp
}

recursiveClean ()
{
	rm -fr $BASEDIR/documentation/api
	rm -fr $BASEDIR/doc/statcvs
	rm -fr $BASEDIR/doc/xref
	while [ $# != 0 ] 
	do
		if [ -f "$1" ] 
		then
			file=$1
			ext=${file##*.}
			case $ext in 
				php | txt | css | js | inc | sql | html | htm | pl | sh | xml | xsl | dtd) 
					# Don't dos2unix this file while executing!
					#if [ $1 != "mkrelease.sh" ]
					#then
						# dos2unix "`pwd`/$1"
					#fi
					;;
				jpg | gif | png | ico) 
					# ignore images
					;;
				*) 
					echo "Unknown file `pwd`/$1"
					;;
			esac
		fi
		if [ -d "$1" ]
		then
			cd $1
			recursiveClean `ls`
			cd ..
		fi
		shift
	done
}

pack ()
{
		cd $BASEDIR/..
		tar cf brim-$TODAY.tar brim
		gzip brim-$TODAY.tar
}

generateAPIDocumentation ()
{
	mkdir $BASEDIR/documentation/api
	$PHPDOCU_HOME/phpdoc 								\
			--title Brim API							\
			--parseprivate on							\
			--directory $BASEDIR 						\
			--ignore 									\
				*ext*,	 								\
				*magpierss*,							\
				*Contact_Vcard*,						\
				*MagpieRSS*,							\
				*RSSCache*,								\
				*Contact_Vcard_Build.php,				\
				*Contact_Vcard_Parse/php				\
			--target $BASEDIR/documentation/api			\
			--output HTML:frames:phphtmllib				\
			--sourcecode on								\
			--quiet off									\
			--readmeinstallchangelog					\
		   		README, 								\
				documentation/installation_guide.html,	\
			   	documentation/changelog.txt 			\
			-dc defaultcategory
}

#recursiveClean $BASEDIR
rm -fr ../documentation/api
rm -fr ../documentation/statcvs
rm -f ../framework/configuration/databaseConfiguration.php
pack
#generateAPIDocumentation
