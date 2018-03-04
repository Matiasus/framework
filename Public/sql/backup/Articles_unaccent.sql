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
CREATE TABLE IF NOT EXISTS Articles_unaccent
(
	Id INT AUTO_INCREMENT NOT NULL,
	Id_articles INT NOT NULL,
	Title VARCHAR(100),
	Content TEXT,
	Category Varchar(200),
	PRIMARY KEY(Id),
	FOREIGN KEY(Id_articles) REFERENCES Articles(Id)
)ENGINE = INNODB;
