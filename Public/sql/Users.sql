/****************************************************
## Copyright (C) 2015 Poznamkovyblog
##
## @Author:     Matiasus
## @Date:       2018-02-17
## @Web:        <http://>
## @Table:      Users
## 
## Remove:
##  mysql> source path_to_folder/Users.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Users
(
  Id INT AUTO_INCREMENT NOT NULL,
  Email VARCHAR(255),
  Username VARCHAR(255),
  Passwordname VARCHAR(255),
  Registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  Codevalidation VARCHAR(255),
  Validation ENUM('valid', 'invalid') DEFAULT 'invalid',
  Privileges ENUM('admin', 'user') DEFAULT 'user',
  PRIMARY KEY(Id)
)ENGINE = INNODB;
