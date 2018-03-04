/****************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-03-11
## Web: 				<http://>
##	
## ##################################################
## # Tabulka - Profiles 														#
## ##################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Profiles.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Profiles
(
	Id INT AUTO_INCREMENT NOT NULL,
  Id_Users INT,
	Name VARCHAR(20),
	Surname VARCHAR(25),
	Birthdate TIMESTAMP DEFAULT NULL,
	Privileges ENUM('admin', 'user') DEFAULT 'user',
	PRIMARY KEY(Id),
  FOREIGN KEY(Id_Users) REFERENCES Users(Id)
)ENGINE = INNODB;
