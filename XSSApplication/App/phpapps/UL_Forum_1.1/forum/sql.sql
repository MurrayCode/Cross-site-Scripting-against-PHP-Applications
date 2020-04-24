SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE DATABASE IF NOT EXISTS `ULforum`;
use `ULforum`;

CREATE TABLE IF NOT EXISTS `flag` (
  `vid` int(11) NOT NULL,
  `user` text NOT NULL,
  `key` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;


CREATE TABLE IF NOT EXISTS `frnd` (
  `id` varchar(20) NOT NULL,
  `fid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `login` (
  `id` varchar(10) NOT NULL,
  `pass` varchar(8) NOT NULL,
  `pass2` varchar(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  `msg` varchar(160) NOT NULL,
  `tym` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `msg` (
  `dest` varchar(10) NOT NULL,
  `sms` varchar(160) NOT NULL,
  `src` varchar(10) NOT NULL,
  `tym` varchar(20) NOT NULL,
  `idx` int(4) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;


CREATE TABLE IF NOT EXISTS `options` (
  `vid` int(11) NOT NULL,
  `opt_id` int(11) NOT NULL,
  `opt_text` text NOT NULL,
  `hits` int(11) NOT NULL,
  `key` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;


CREATE TABLE IF NOT EXISTS `topic` (
  `msg` text NOT NULL,
  `tym2` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tid` int(11) NOT NULL,
  `idx` int(3) NOT NULL AUTO_INCREMENT,
  `user` varchar(10) NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;


CREATE TABLE IF NOT EXISTS `topic2` (
  `name` varchar(60) NOT NULL,
  `user` varchar(10) NOT NULL,
  `cr_time` datetime NOT NULL,
  `cat` varchar(3) NOT NULL,
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `up_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;


CREATE TABLE IF NOT EXISTS `topic_list` (
  `Title` varchar(20) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`Title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `votes` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `vtext` text NOT NULL,
  `vopts` int(11) NOT NULL,
  `user` text NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;