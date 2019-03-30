/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2018-02-17
## @Web:        <http://>
## @Table:      Logins
## 
## Remove:
##  mysql> source path_to_folder/Logins.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS `Logins` (
  `Sessionid` varchar(30) NOT NULL,
  `Id_Users` int(11) DEFAULT NULL,
  `Login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Logoff` timestamp NULL DEFAULT NULL,
  `Ip_address` varchar(15) DEFAULT NULL,
  `User_agent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (Sessionid),
  FOREIGN KEY (Id_Users) REFERENCES Users (Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
