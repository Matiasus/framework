/****************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-03-11
## Web: 				<http://>
##	
## ##################################################
## # Tabulka - Categories														#
## ##################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Categories.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Categories
(
	Id INT AUTO_INCREMENT NOT NULL,
	Doprava VARCHAR(255),
	MHD VARCHAR(255),
	PRIMARY KEY(Id)
);
