/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2019-03-29
## @Web:        <http://>
## @Table:      Components
## 
## Launch:
##  mysql> source path_to_folder/Components.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS `Components` (
  `Id` int(11) NOT NULL,
  `Id_Receivers` int(11),
  `Category` varchar(25) DEFAULT NULL,
  `Category_unaccent` varchar(25) NOT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Description_unaccent` varchar(100) NOT NULL,
  `Label` varchar(200) DEFAULT NULL,
  `Amount` int(11) NOT NULL,
  `Registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Edited` timestamp NULL DEFAULT NULL,
  PRIMARY KEY(Id),
  FOREIGN KEY(Id_Receivers) REFERENCES Receivers(Id)
) ENGINE=INNODB;
