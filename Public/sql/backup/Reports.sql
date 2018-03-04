/****************************************************
## Copyright (C) 2015 Infoservis
##
## Author: 			Infoservis Group
## Date:				2015-03-11
## Web: 				<http://>
##	
## ##################################################
## # Tabulka - Reports															#
## ##################################################
## 
## Vytvorenie mozne v mysql prikazom:
##  mysql> source adresa_k_priecinku/Reports.sql
##
****************************************************/
CREATE TABLE IF NOT EXISTS Reports
(
	Id INT AUTO_INCREMENT NOT NULL,
	Created TIMESTAMP DEFAULT NOW(),
	Creator INT NOT NULL,
	Duration INT NOT NULL,
	Location_latitude DOUBLE,
	Location_longitude DOUBLE,
	Location_range INT,
	Report_counter INT DEFAULT 0,
	Report_title VARCHAR(100),
	Report_text TEXT,
	Report_category INT NOT NULL,
	PRIMARY KEY(Id),
	FOREIGN KEY(Creator) REFERENCES Users(id),
	FOREIGN KEY(Report_category) REFERENCES Categories(id)
);
