If you are upgrading (especially when upgrading from Booby!!!), please 
read the UPGRADE.txt file)

Additional documentation can be found in the documentation subdirectory:
documentation/changelog.txt
documentation/booby2brimchanges.txt


*** Quick install/update 
- copy the file the file 'framework/configuration/databaseConfiguration.example.php'
to 'framework/configuration/databaseConfiguration.php' and edit its contents
- execute the 'install.php' file in the root directory
(also if you are upgrading). 
- Remove the install.php script
- login using username and password 'admin'. If you performed an upgrade, the old 
credentials are still available.
- Set the installation path and admin email (if applicable, see previous step) in 
the configuration section (you must be logged as admin)

Questions/Bugs and or Feature requests can be posted in the forum,
accessible via http://www.brim-project.org/

A script (email2brim.pl) is available in the tools subdirectory. This
script fetches information from email and 'slams' it into the application. 
If a subject starts with [bookmark], [task], [contact] or [note] it is 
automatically inserted in the appropriate plugin.
Put this script in your crontab to use it to its full extend!
You need to modify the email settings in this script!

It is possible to publish your bookmarks. The link in the bookmarks 
section called 'Your public bookmarks' (in your language of course) 
contains the URL. The file 'brim.php' shows how you can embed
the public bookmarks in a webpage. If you want an example, go to
http://www.barrel.net/links.php
A Wordpress plugin is now also available that does more or less the 
same thing, except that it is embedded (and managed) from within 
a wordpress site. Check my homepage (http://barry.nauta.be/) for a demo

The latest information is always available on the website:
http://www.brim-project.org

Subscription to new releases via either Sourceforge or Freshmeat:
http://freshmeat.net/projects/brim
http://sourceforge.net/projects/brim

Enjoy :-)
Barry ( barry |at| nauta |dot| be )
