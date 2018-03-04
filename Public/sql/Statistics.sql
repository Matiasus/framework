/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2018-02-17
## @Web:        <http://>
## @Table:      Authentication
## 
## Remove:
##  mysql> source path_to_folder/Authentication.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Visits
(
  Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL,
  Logins_Session VARCHAR(30) NOT NULL,
  Page VARCHAR(100) NOT NULL,
  Arrival TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Id)
)ENGINE = INNODB;
