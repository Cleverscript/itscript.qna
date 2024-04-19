CREATE TABLE IF NOT EXISTS `b_itscript_question` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `USER_ID` int NOT NULL,
    `ENTITY_ID` int NOT NULL,
    `ACTIVE` varchar(1) NOT NULL,
    `URL` varchar(1000) NOT NULL,
    `QUESTION` varchar(8000) NOT NULL,
    `ANSWER` varchar(8000) NOT NULL,
    `PUBLISH_DATE` TIMESTAMP NOT NULL,
    PRIMARY KEY (`ID`)
);