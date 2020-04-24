This is the third public release of ISM. Installation direction are below in the first release notes. (At the bottom.)

Changelog:
-Fixed the "earlier articles" bug. Clicking the "Earlier articles" link at the bottom of the main page would only take you back until their was fewer than 10 articles left. Now it will list all articles.
-The theming engline has been rewritten to be more clear. The light_blue theme has been spruced up a  bit.
-RSS feeds are now generated automatically whenever an article is create/updated/deleted. IT creates a file named rss.xml in the same director as index.php.
-What tags are allowed in the article and comment body text is now a variable in the site config section.
-The "read more" links now have a small margin on their right.
-The arrows after "read more" are unicode escape characters now. It doesn't seem to work i explorer though, gives a box.
-All visits are now recorded (instead of just to the main page). The admin page also has a box showing the most frequently-visited articles.
-Added a link to the sourceforge page for ISB at the bottom of the page.
-Looked into the nr2br() function, but it's only PHP5, so not used here.
-The published field is now a drop-down menu on the edit and add pages.
-Articles and comments are scanned for HTML tags that aren't closed, and clsing tags added to the end of the text.

Oustanding Issues:
-If there is a "quoted" section in the title, it doesn't show up when editing. 
-The if/else ifs need to be made into switches
-Escaped "<" in an article come out as unescaped "<" when editing, and are saved back as unescaped "<"
-Stats could get messy with every visit to everypage being recorded. There should be a "clear stats" admin option
-Searches it " or ' do not return anything
-The select boxes on the add/edit article look bad - get a thin line around them instead of a bevel.
-The unicode arrows need to work in IE.

------------------------
Previous release notes
------------------------

This is the second release of ISB. Installation directions are below in the first release notes.

Changelog:
1. Added some more theming abilities.
2. Fixed multiple slash (escape string) issues.
3. Added "Previous Articles" links.
4. Added search bar.

----------

This is the first public release of Insanely Simple Blog code and sample database. It is done with very little documentation. All questions should be addressed through the Source Forge site: http://sourceforge.net/projects/insanelysimple2/ .

Basically, just grab the .zip, run the .sql file through MySQL, and put the .php file on a web server. Edited the MySQL variables at the top of the .php file to reflect your MySQL server setup, and view the page in a web browser.

More details will come shortly.

-jb