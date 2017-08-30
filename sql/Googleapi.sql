/******************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-07-11
## Web: 				<http://>
##	
## ####################################################
## # Tabulka - Authentication												  #
## ####################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Googleapi.sql
##
******************************************************/
CREATE TABLE IF NOT EXISTS Googleapi
(
    Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL,
    Usersid INT(11) NOT NULL,
    Client_id VARCHAR(200),
		Client_secret VARCHAR(200),
		Refresh_token VARCHAR(200),
		Redirect_uri VARCHAR(200),
		Scopes VARCHAR(200),
		Registered TIMESTAMP NOT NULL ON UPDATE now(), 
    PRIMARY KEY (Id),
		FOREIGN KEY(Usersid) REFERENCES Users(Id)
)ENGINE = INNODB;
