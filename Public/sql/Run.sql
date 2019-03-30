/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2019-03-16
## @Web:        <http://>
## @Table:      Run
## 
## Launch:
##  mysql> source path_to_folder/Run.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS `Run` (
  `Id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Id_Users` int(11) NOT NULL,
  `Category` varchar(25) DEFAULT NULL,
  `Category_unaccent` varchar(25) CHARACTER SET utf32 NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Title_unaccent` varchar(100) NOT NULL,
  `Registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Edited` timestamp NULL DEFAULT NULL,
  FOREIGN KEY(Id_Users) REFERENCES Users(Id)
) ENGINE=INNODB;
