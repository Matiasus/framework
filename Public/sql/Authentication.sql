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
CREATE TABLE IF NOT EXISTS Authentication
(
  Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL,
  Token VARCHAR(255),
  Id_Users INT(11) NOT NULL,
  Expire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  Sessionid VARCHAR(30) NOT NULL,
  Current TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  Last TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Id),
  FOREIGN KEY(Id_Users) REFERENCES Users(Id)
)ENGINE = INNODB;
