/******************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-03-11
## Web: 				<http://>
##	
## ####################################################
## # Tabulka - Authentication												  #
## ####################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Authentication.sql
##
******************************************************/
CREATE TABLE IF NOT EXISTS Authentication
(
    Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL,
    Token VARCHAR(255),
    Id_Users INT(11) NOT NULL,
    Expires TIMESTAMP NULL,
		Session VARCHAR(30) NOT NULL,
		Current TIMESTAMP NOT NULL ON UPDATE now(),
		Last TIMESTAMP NULL,
		PRIMARY KEY (Id),
		FOREIGN KEY(Id_Users) REFERENCES Users(Id)
)ENGINE = INNODB;
