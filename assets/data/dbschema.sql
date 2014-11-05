-- Create database
CREATE DATABASE IF NOT EXISTS `FabricTech_MWadeson`;

-- Create Landlord table
CREATE TABLE IF NOT EXISTS `FabricTech_MWadeson`.`tblLandlord` (
	`LandlordID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`LandlordName` VARCHAR(255) NOT NULL
);

-- Create Landlord Group table
CREATE TABLE IF NOT EXISTS `FabricTech_MWadeson`.`tblLandlordGroup` (
	`LandlordGroupID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`LandlordGroupName` VARCHAR(255) NOT NULL
);

-- Create Landlord, Landlord Group relationship table
CREATE TABLE IF NOT EXISTS `FabricTech_MWadeson`.`tblLandlordGroupRel` (
	`LandlordID` INT NOT NULL,
	`LandlordGroupID` INT NOT NULL,
	FOREIGN KEY (`LandlordID`) REFERENCES `FabricTech_MWadeson`.`tblLandlord`(`LandlordID`),
	FOREIGN KEY (`LandlordGroupID`) REFERENCES `FabricTech_MWadeson`.`tblLandlordGroup`(`LandlordGroupID`)
);

-- Create Costs table
CREATE TABLE IF NOT EXISTS `FabricTech_MWadeson`.`tblCosts` (
	`CostsID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`LandlordID` INT NOT NULL,
	`CostsQuarter` TINYINT(1) SIGNED NOT NULL,
	`CostsYear` SMALLINT(4) SIGNED NOT NULL,
	`CostsEstimated` INT NOT NULL DEFAULT 0,
	`CostsActual` INT NOT NULL DEFAULT 0,
	FOREIGN KEY (`LandlordID`) REFERENCES `FabricTech_MWadeson`.`tblLandlord`(`LandlordID`)
);