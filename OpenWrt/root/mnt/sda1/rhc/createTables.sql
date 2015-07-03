
-- if statement have warnings here how you show them:
--SHOW WARNINGS\G;

CREATE SCHEMA `arduino` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
use arduino;

-- Table: logs
CREATE TABLE `logs` (
  `logId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `logDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `logDetail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`logId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO logs(`logDetail`)VALUES('crated table');

-- Table: settings
CREATE  TABLE `settings` (
  `settingsId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `mykey` VARCHAR(45) NOT NULL ,
  `myvalue` VARCHAR(45) NOT NULL ,
  `settingdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`settingsId`) ,
  UNIQUE INDEX `mykey_UNIQUE` (`mykey` ASC)
) ENGINE = MyISAM AUTO_INCREMENT=1;

INSERT INTO settings ( mykey, myvalue) VALUES ('ExtendedLight', 'Y');
INSERT INTO settings ( mykey, myvalue) VALUES ('ExtendedLightStartTime', '18:00:00');
INSERT INTO settings ( mykey, myvalue) VALUES ('ExtendedLightMinLux', '800');
INSERT INTO settings ( mykey, myvalue) VALUES ('ExtendedLightEndTime', '20:00:00');

-- Table: device
CREATE  TABLE `device` (
  `deviceId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,    
  `deviceName`  VARCHAR(45) NOT NULL ,
  `deviceIP`  VARCHAR(45) NOT NULL ,
  `deviceDescription`  VARCHAR(45) NOT NULL COMMENT 'store IP address or description here',  
  PRIMARY KEY (`deviceId`)
) ENGINE = MyISAM AUTO_INCREMENT=1;

 INSERT INTO device (deviceName, deviceIP, deviceDescription) VALUES ('Arduino Yun GreenHouse', '192.168.1.187', 'Greenhouse logger');
 INSERT INTO device (deviceName, deviceIP, deviceDescription) VALUES ('Arduino Mega Utility Room', '192.168.1.120', 'Flood Detector');

-- Table: sensor
CREATE  TABLE `sensor` (
  `sensorId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `deviceId` INT NOT NULL COMMENT 'which device ID sensor belongs to' ,
  `sensorAddress` VARCHAR(45) NULL COMMENT 'If sensor has address store it here',
  `sensorName`  VARCHAR(45) NOT NULL ,
  `sensorType`  VARCHAR(45) NOT NULL  COMMENT 'Type as Temperature, Humidity, Distance, etc... ',
  `sensorUnit` VARCHAR(15) NOT NULL COMMENT 'Unit of measurement lux, Celsius, On/Off, etc...',
  PRIMARY KEY (`sensorId`)
) ENGINE = MyISAM AUTO_INCREMENT=1;

INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '6CD', 'Temp1', 'DS18B20', 'Celsius'); --1
INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '6D5', 'Temp2', 'DS18B20', 'Celsius'); --2
INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '6DD', 'Temp3', 'DS18B20', 'Celsius'); --3
INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '6E5', 'Temp4', 'DS18B20', 'Celsius'); --4
INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '6ED', 'Temp5', 'DS18B20', 'Celsius'); --5

INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '', 'Temp', 'DHT', 'Celsius');      --6
INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '', 'Humidity', 'DHT', '%');               --7
INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '', 'Heat Index', 'DHT', 'Celsius');       --8 

INSERT INTO sensor (deviceId, sensorAddress, sensorName, sensorType, sensorUnit) VALUES (1, '', 'Light', 'photocell', 'Luminas'); --9

-- Table: sensorLog
CREATE  TABLE `sensorLog` (
  `logId` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
  `logDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `jobName`  VARCHAR(45) NOT NULL COMMENT 'Job that triggered this log entry',  
  PRIMARY KEY (`logId`)
) ENGINE = MyISAM AUTO_INCREMENT=1;


-- Table: sensorValue
CREATE  TABLE `sensorValue` (
  `sensorValueId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `logId` INT UNSIGNED NOT NULL,
  `sensorId` INT UNSIGNED NOT NULL ,
  `value` DECIMAL(6,2) NOT NULL ,  
  PRIMARY KEY (`sensorValueId`)
) ENGINE = MyISAM AUTO_INCREMENT=1;


# to make log entry first add sensorLog reord:
INSERT INTO sensorLog (jobName) VALUES ('cronos 20min job');
# then add for each sensor the measured value:
INSERT INTO sensorValue (logId, sensorId, value) VALUES ( );


-- Index: idx_sensorLog
CREATE INDEX idx_sensorLog ON sensorLog ( 
    logDate 
);

-- Index: idx_sensorValue
CREATE INDEX idx_sensorValue ON sensorLog ( 
    logDate 
);