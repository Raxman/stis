--
-- STIS dump for database MySQL
--
-- ------------------------------------------------------

DROP DATABASE IF EXISTS `stis`;
CREATE DATABASE `stis`;

USE stis;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `departmentid` int(11) NOT NULL auto_increment,
  `department` varchar(50) default NULL,
  PRIMARY KEY  (`departmentid`)
) ENGINE=MyISAM DEFAULT CHARSET=koi8r;

--
-- Table structure for table `logaccess`
--

DROP TABLE IF EXISTS `logaccess`;
CREATE TABLE `logaccess` (
  `date` date default NULL,
  `time` time default NULL,
  `response` decimal(10,0) default NULL,
  `clientip` varchar(15) default NULL,
  `squidcode` varchar(25) default NULL,
  `httpcode` decimal(3,0) default NULL,
  `bytes` double default NULL,
  `method` varchar(10) default NULL,
  `url` text,
  `user` varchar(15) default NULL,
  `hierarchy` varchar(25) default NULL,
  `requestip` varchar(15) default NULL,
  `content` varchar(15) default NULL,
  `type` varchar(20) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=koi8r;

--
-- Table structure for table `loghosts`
--

DROP TABLE IF EXISTS `loghosts`;
CREATE TABLE `loghosts` (
  `date` date default NULL,
  `host` varchar(15) default NULL,
  `loadbytes` double default NULL,
  `cachebytes` double default NULL
) ENGINE=MyISAM DEFAULT CHARSET=koi8r;

--
-- Table structure for table `logusers`
--

DROP TABLE IF EXISTS `logusers`;
CREATE TABLE `logusers` (
  `date` date default NULL,
  `user` varchar(15) default NULL,
  `loadbytes` double default NULL,
  `cachebytes` double default NULL
) ENGINE=MyISAM DEFAULT CHARSET=koi8r;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user` char(15) NOT NULL,
  `password` char(17) default NULL,
  `admin` enum('N','Y') NOT NULL default 'N',
  `fullname` varchar(50) default NULL,
  `departmentid` int(11) default NULL,
  `maxdaily` double default NULL,
  `maxmonthly` double default NULL,
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=koi8r;

INSERT INTO users (user, password, admin) VALUES ('admin',password('admin'),'Y');

GRANT ALL PRIVILEGES ON stis.* TO 'stis'@'localhost' IDENTIFIED BY 'stis';
