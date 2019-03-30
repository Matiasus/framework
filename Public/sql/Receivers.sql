/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2019-03-29
## @Web:        <http://>
## @Table:      Receivers
## 
## Launch:
##  mysql> source path_to_folder/Components.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS `Receivers` (
  `Id` int(11) NOT NULL,
  `Mark` varchar(100) DEFAULT NULL,
  `Mark_unaccent` varchar(100) DEFAULT NULL,
  `Type` varchar(100) DEFAULT NULL,
  `Type_unaccent` varchar(100) DEFAULT NULL,
  `Year` varchar(100) DEFAULT NULL,
  `Power` varchar(100) NOT NULL,
  `Registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Edited` timestamp NULL DEFAULT NULL,
  PRIMARY KEY(Id),
) ENGINE=INNODB;
