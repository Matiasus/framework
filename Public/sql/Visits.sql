/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2019-03-29
## @Web:        <http://>
## @Table:      Visits
## 
## Remove:
##  mysql> source path_to_folder/Visits.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS `Visits` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Sessionid_Logins` varchar(30) NOT NULL,
  `Page` varchar(200) DEFAULT NULL,
  `Arrival` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Id),
  FOREIGN KEY (Sessionid_Logins) REFERENCES Logins (Sessionid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
