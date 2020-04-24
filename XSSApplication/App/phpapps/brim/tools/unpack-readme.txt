Upload the following three files to your webserver:

- brimyyyy-xxx.tar.gz  (yyy is either 'full' or 'lite', xxx is a versionnumber with a date)
- Tar.php
- unpack.php

The package will unpack in a subdirectory called brim, upload the 
files one level higher than where you would like your application to be.
For instance: to have the application available at 
http://yoursite.com/brim and your webroot is /home/yourname/public_html 
you should upload your files to /home/yourname/public_html and not 
something like /home/yourname/public_html/brim!

Make sure those three files are accesible by the webserver (on *nix 
machines: chmod 644)

In the directory where you just uploaded the files, create a directory
called 'brim' and make sure that it is writeable by the webserver 
(on *nix machines: chmod 777 will do, but please note that some webservers are not too happy
about this. Alternatively try 755 instead).
The script will unpack the files in this directory. Note that any 
existing will be overwritten! (The package does not contain the 
databaseConfiguration file, so don't worry ;-)

Execute the unpack.php file in your webbrowser 
(ex: http://yoursite.com/unpack.php). The script will ask you to
specify the _exact_ name of the file you wish to unpack, which is the
brimfull-xxx.tar.gz file you just uploaded.

If all goes well, the sript will tell you that you now execute the 
installation script.

Before doing this:
remove the three uploaded files
Goto the brim application directory and in the directory
framework/configuration rename (or copy) the 
databaseConfiguration.example.php file to a file with the name
databaseConfiguration.php and edit it (provide database engine (like mysql or postgres), 
database username, database password, database host (typically localhost) and database name (i.e. brim)).

The installation script will setup the application. 

Good luck! 
Any feedback (bugs, improvements etc) are appreciated, but please
do so via the sourceforge website: http://sourceforge.net/projects/brim


---
Barry Nauta
barry@nauta.be

Brim project
http://www.brim-project.org
