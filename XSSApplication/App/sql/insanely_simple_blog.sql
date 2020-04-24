-- MySQL dump 10.9
--
-- Host: localhost    User: root 	 password: hacklab2019		Database: insanely_simple_blog
-- ------------------------------------------------------
-- Server version	4.1.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `insanely_simple_blog`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `insanely_simple_blog` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;

USE `insanely_simple_blog`;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `article_id` int(11) NOT NULL default '0',
  `posted_by` varchar(32) collate latin1_general_ci NOT NULL default '',
  `parent_id` bigint(20) NOT NULL default '0',
  `subject` varchar(128) collate latin1_general_ci NOT NULL default '',
  `content` blob NOT NULL,
  `date_posted` int(14) unsigned NOT NULL default '0',
  `date_modified` int(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `article` (`article_id`,`parent_id`),
  KEY `parent` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `comments`
--


/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
LOCK TABLES `comments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section` varchar(32) collate latin1_general_ci NOT NULL default '',
  `subsection` varchar(32) collate latin1_general_ci NOT NULL default '',
  `date_posted` int(14) unsigned NOT NULL default '0',
  `date_modified` int(14) unsigned default '0',
  `author` varchar(32) collate latin1_general_ci NOT NULL default '',
  `title` varchar(128) collate latin1_general_ci NOT NULL default '',
  `content` blob NOT NULL,
  `published` enum('No','Yes') collate latin1_general_ci NOT NULL default 'No',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`,`section`,`subsection`,`date_posted`,`date_modified`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `content`
--


/*!40000 ALTER TABLE `content` DISABLE KEYS */;
LOCK TABLES `content` WRITE;
INSERT INTO `content` VALUES (1,'Help','How to...',1124308876,1124310922,'jb','How to add content','To add content, click on the \"Site administration\" link below, on the left. From there, you can choose to add new content/links, or edit/delete preexisting entries on the site. You do not have to add sections and subsections because each entry is grouped by the section names you enter when you add content. If your new entry doesn\'t fit into the same section or subsection of any of your previous entries, simply enter a different (sub)section name when you create the entry, and it will appear in the sections list.\r\n\r\nIf you are editing/adding content, the \"Published\" field should be \"yes\" or \"no\". Articles marked \"no\" will not appear on the blog\'s front page, or in any of its sections.\r\n\r\nThe password is set in the index.php page, in the site variables section. You will need this password to be able to change anything on this site.','Yes'),(2,'Insanely Simple Blog Info','ISB Development',1124309475,1124309598,'jb','How ISB came to be','Insanely Simple Blog is for anyone who is tired of trying to kill a mosquito with a cannon ball. It is a really simple MySQL/PHP page that offers a fully functional single-user blog, with sections, subsections and nested commenting. \r\n\r\nISB was originally coded back in the Winter of 2003, one boring Saturday. I used it for a time as my personal blog, and even extended it into a knowledge base for the lab where I worked, but I eventually moved on to <a href=\"http://phpwebsite.appstate.edu/\">phpWebSite</a> (a full content management system which I still highly recommend) for my personal site, but using it for, what was essentially a blog was harder than it was worth.\r\n\r\nRecently, the lab I had worked at asked me to reconfigure their site, which used the grandfather of ISB code, and I realized how simple it was to use. Granted, it\'s only single-user, and doesn\'t handle images, unless you write your own img tags, but you can publish an entry in two clicks, plus the time to fill in the fields.\r\n\r\nSo, I revisited the code, polished it up and commented it. Here it is.\r\n\r\nBe sure to visit the getting started article and the how to\'s in the help section if you need any, and you can reach me through my sourceforge email.','Yes'),(3,'Help','Getting Started',1124310215,0,'jb','Step 1: Settting Site Variables','If you can read this, you\'ve downloaded the package, run the .sql file and set all the necessary permissions in MySQL, and visited this site for the first time. Now, where do you go from here?\r\n\r\nFirst, you will need to set the site variables in the index.php file. They are located at the top, and there is a lot of white space after them, so you can\'t miss them. There are 12 of them, but four are required to properly connect to your MySQL server, so you should have knocked them down already.\r\n\r\nFirst, you\'ll want to set your blog\'s title to something more interesting than “ISB Example”:\r\n$site_title = \"ISB Example\"; \r\n\r\nThis title appears in the HTML title tag, and at the top of every page. Do to some current limitations in ISB\'s stylesheets, long, multiword titles may cause some problems.\r\n\r\nThen, you may want to put your name and email on the site. This is completely optional:\r\n$content_owner = \"John Doe\"; \r\n$owner_email = \"\"; \r\n\r\nThe admin password must be changed if you hope to keep any control over your site. Every ISB site has the same default password, so you need to change this ASAP.\r\n$site_admin_password = \"qwerty\"; \r\n\r\nFinally, there are four layout variables. Do you want to allow comments on your blog?\r\n$allow_comments = \"yes\"; \r\n\r\nDo you want the admin link to show up on the blog? If not, you can still administer your blog by going to index.php?action=admin.\r\n$show_admin_link = \"yes\";\r\n\r\nHow many articles should show on each page?\r\n$articles_per_page = 10; \r\n\r\nAnd, finally, what color do you want you blog to be?\r\n$color_scheme = \"blue\"; \r\n\r\nNow your blog is ready for your content. See the articles in the How To... section for details.','Yes');
UNLOCK TABLES;
/*!40000 ALTER TABLE `content` ENABLE KEYS */;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` int(11) NOT NULL auto_increment,
  `link_text` varchar(64) collate latin1_general_ci NOT NULL default '',
  `section` varchar(32) collate latin1_general_ci NOT NULL default '',
  `url` varchar(128) collate latin1_general_ci NOT NULL default '',
  `date_posted` int(14) unsigned NOT NULL default '0',
  `date_modified` int(14) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `section` (`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `links`
--


/*!40000 ALTER TABLE `links` DISABLE KEYS */;
LOCK TABLES `links` WRITE;
INSERT INTO `links` VALUES (1,'Source Forge','Resources','http://sourceforge.net',1124308912,1124309019),(2,'Insanely Simple Blog','Resources','http://sourceforge.net/projects/insanelysimple2/',1124309003,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `links` ENABLE KEYS */;

-- 
-- Table structure for table `visits`
-- 

CREATE TABLE `visits` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date` int(10) unsigned NOT NULL default '0',
  `ip` varchar(16) collate latin1_general_ci NOT NULL default '',
  `site` varchar(32) collate latin1_general_ci default NULL,
  `article_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `date` (`date`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3103 ;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

