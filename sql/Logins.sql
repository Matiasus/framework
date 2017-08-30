/****************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-03-11
## Web: 				<http://>
##	
## ##################################################
## # Tabulka - Logins   														#
## ##################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Logins.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Logins
(
	Id INT AUTO_INCREMENT NOT NULL,
  Id_Users INT,
	Datum TIMESTAMP DEFAULT NOW(),
	Ip_address VARCHAR(15),
	User_agent VARCHAR(100),
	PRIMARY KEY(Id),
  FOREIGN KEY(Id_Users) REFERENCES Users(Id)
)ENGINE = INNODB;
