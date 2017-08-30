/****************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-07-26
## Web: 				<http://>
##	
## ##################################################
## # Tabulka - Articles															#
## ##################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Articles.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Articles
(
	Id INT AUTO_INCREMENT NOT NULL,
	Usersid INT NOT NULL,
	Title VARCHAR(100),
	Content TEXT,
	Category Varchar(200),
	Registered TIMESTAMP DEFAULT NOW(),
	PRIMARY KEY(Id),
	FOREIGN KEY(Usersid) REFERENCES Users(Id)
)ENGINE = INNODB;
