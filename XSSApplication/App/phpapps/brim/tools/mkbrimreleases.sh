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
BASEDIR=`pwd`
TODAY=`date '+%d_%b_%Y'`;

packfull ()
{
		tar cf brimfull-$TODAY.tar brim
		gzip brimfull-$TODAY.tar
		mv brimfull-$TODAY.tar.gz $BASEDIR/../..
}

packlite ()
{
		tar cf brimlite-$TODAY.tar brim
		gzip brimlite-$TODAY.tar
		mv brimlite-$TODAY.tar.gz $BASEDIR/../..
}

rm -fr brim

svn export https://svn.sourceforge.net/svnroot/brim/trunk/brim brim
svn export https://svn.sourceforge.net/svnroot/brim/trunk/wordpress wordpress
#cvs -z3 -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/brim export -r HEAD brim
#cvs -z3 -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/brim export -r HEAD wordpress

mv wordpress/brim-wordpress.php brim/tools
mv wordpress/brim-wordpress.txt brim/tools
rm -fr wordpress

chmod -x brim/BankingController.php
rm -fr brim/plugins/genealogy
rm -fr brim/plugins/trash

rm -f brim/tools/plugins-basic.php
rm -f brim/tools/install-basic.php 
rm -f brim/tools/mkapi.sh
rm -f brim/tools/mkbrimlite.sh
rm -f brim/tools/mkbrimfull.sh
rm -f brim/tools/delete.php.x

rm -f brim/documentation/pics/question_booby.jpg
rm -f brim/documentation/pics/question_booby.gif
rm -f brim/documentation/pics/feet_booby.jpg

rm -fr brim/ext/simpletest brim/test.php
rm -fr brim/tools/statcvs.sh
rm -fr brim/tools/dict.sh
rm -fr brim/tools/dev.sh
rm -fr brim/tools/cvsexport.sh
rm -fr brim/tools/mkapi.sh
rm -fr brim/tools/mkbrimlite.sh
rm -fr brim/tools/mkbrimfull.sh
rm -fr brim/tools/Tar.php
rm -fr brim/tools/unpack.*
rm -fr brim/tools/phpdoc_zend.cfg

packfull

rm -fr brim/BankingController.php brim/plugins/banking
rm -fr brim/CheckbookController.php brim/plugins/checkbook
rm -fr brim/CollectionsController.php brim/plugins/collections
rm -fr brim/GMailController.php brim/plugins/gmail
rm -fr brim/NewsController.php brim/plugins/news
rm -fr brim/PasswordController.php brim/plugins/passwords
rm -fr brim/WebtoolsController.php brim/plugins/webtools
rm -fr brim/DepotController.php brim/plugins/depot
rm -fr brim/plugins/recipes
rm -fr brim/plugins/weather
rm -fr brim/templates/text-only

rm -f brim/documentation/design.html brim/documentation/pics/design.png

# webtools 
rm -fr brim/ext/javascript/pics
rm -fr brim/ext/javascript/boxsizing.htc brim/ext/javascript/colormatch.js 
rm -fr brim/ext/javascript/range.js brim/ext/javascript/slider.js 
rm -fr brim/ext/javascript/sliderSwing.css brim/ext/javascript/sliderWindowsClassic.css 
rm -fr brim/ext/javascript/timer.js

packlite

cp Tar.php $BASEDIR/../..
cp unpack.php $BASEDIR/../..
cp unpack-readme.txt $BASEDIR/../..
cp quickinstall.txt $BASEDIR/../..
cd $BASEDIR/../..
zip brimfull-$TODAY.zip Tar.php unpack.php unpack-readme.txt quickinstall.txt brimfull-$TODAY.tar.gz 
zip brimlite-$TODAY.zip Tar.php unpack.php unpack-readme.txt quickinstall.txt brimlite-$TODAY.tar.gz 

rm brimfull-$TODAY.tar.gz
rm brimlite-$TODAY.tar.gz
rm Tar.php
rm unpack.php
rm unpack-readme.txt 
rm quickinstall.txt

cd $BASEDIR
rm -fr brim
