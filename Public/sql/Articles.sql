/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2018-02-17
## @Web:        <http://>
## @Table:      Articles
## 
## Launch:
##  mysql> source path_to_folder/Articles.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS `Articles` (
  `Id` int(11) NOT NULL,
  `Id_Users` int(11) NOT NULL,
  `Category` varchar(25) DEFAULT NULL,
  `Category_unaccent` varchar(25) CHARACTER SET utf32 NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Title_unaccent` varchar(100) NOT NULL,
  `Content` text,
  `Registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Edited` timestamp NULL DEFAULT NULL,
  `Type` enum('draft','released') NOT NULL DEFAULT 'draft'
) ENGINE=INNODB;
