DROP DATABASE IF EXISTS cheapomail;
CREATE DATABASE cheapomail;
USE cheapomail;

-- table 'Users'

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `ID` int NOT NULL auto_increment PRIMARY KEY,
    `firstname` char(255) NOT NULL default '',
    `lastname` char(255) NOT NULL default '',
    `username` char(255) NOT NULL default '',
    `password` char(255) NOT NULL default ''
);

-- table 'Messages'

DROP TABLE IF EXISTS `Messages`;
CREATE TABLE `Messages` (
    `ID` int NOT NULL auto_increment PRIMARY KEY,
    `recID` int NOT NULL default '0',
    `userID` int NOT NULL default '0',
    `subject` char(255) NOT NULL default '',
    `msg` char(255) NOT NULL default '',
    `date_sent` DATE
);

-- table 'Read_msgs'

DROP TABLE IF EXISTS `Read_msgs`;
CREATE TABLE `Read_msgs` (
    `ID` int NOT NULL auto_increment PRIMARY KEY,
    `messageID` int NOT NULL default '0',
    `readerID` int NOT NULL default '0',
    `date_read` DATE
);

-- Add admin 

INSERT INTO `Users` VALUES(1,'admin','admin','admin', sha1('password'));