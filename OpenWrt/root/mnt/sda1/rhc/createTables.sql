
# if statement have warnings here how you show them:
#SHOW WARNINGS\G;

CREATE SCHEMA `arduino` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
use arduino;

#Table: logs
CREATE TABLE `log` (
  `logId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `logDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `logDetail` varchar(100) DEFAULT NULL,
  `logType` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#Index: idx_logType
CREATE INDEX idx_logType ON log ( 
    logType 
);

INSERT INTO log(`logDetail`, `logType`)VALUES('crated table', 0);

#Table: setting
CREATE  TABLE `setting` (
  `settingsId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `mykey` VARCHAR(45) NOT NULL ,
  `myvalue` VARCHAR(45) NOT NULL ,
  `settingdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`settingsId`) ,
  UNIQUE INDEX `mykey_UNIQUE` (`mykey` ASC)
) ENGINE = InnoDB AUTO_INCREMENT=1;

INSERT INTO setting ( mykey, myvalue) VALUES ('ExtendedLight', 'Y');
INSERT INTO setting ( mykey, myvalue) VALUES ('ExtendedLightStartTime', '18:00:00');
INSERT INTO setting ( mykey, myvalue) VALUES ('ExtendedLightMinLux', '800');
INSERT INTO setting ( mykey, myvalue) VALUES ('ExtendedLightEndTime', '20:00:00');

#Table: device
CREATE  TABLE `device` (
  `deviceId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,    
  `deviceName`  VARCHAR(45) NOT NULL ,
  `deviceIP`  VARCHAR(45) NOT NULL ,
  `deviceDescription`  VARCHAR(45) NOT NULL COMMENT 'store IP address or description here',  
  PRIMARY KEY (`deviceId`)
) ENGINE = InnoDB AUTO_INCREMENT=1;

 INSERT INTO device (deviceName, deviceIP, deviceDescription) VALUES ('Arduino Yun GreenHouse', '192.168.1.187', 'Greenhouse logger');
 INSERT INTO device (deviceName, deviceIP, deviceDescription) VALUES ('Arduino Mega Utility Room', '192.168.1.120', 'Flood Detector');

#Table: sensor
CREATE  TABLE `sensor` (
  `sensorId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `deviceId` INT NOT NULL COMMENT 'which device ID sensor belongs to' ,
  `sensorAddress` VARCHAR(45) NULL COMMENT 'If sensor has address store it here',
  `sensorSubAddress` VARCHAR(45) NULL COMMENT 'If sensor has multiple sensors store them here',
  `sensorName`  VARCHAR(45) NOT NULL ,
  `sensorType`  VARCHAR(45) NOT NULL  COMMENT 'Type as Temperature, Humidity, Distance, etc... ',
  `sensorUnit` VARCHAR(15) NOT NULL COMMENT 'Unit of measurement lux, Celsius, On/Off, etc...',
  PRIMARY KEY (`sensorId`)
) ENGINE = InnoDB AUTO_INCREMENT=1;

INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, '28 39 E8 6D 6 0 0 5D', NULL, 'Temp1', 'DS18B20', 'Celsius'); #1
INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, '28 35 2 70 6 0 0 E2', NULL,  'Temp2', 'DS18B20', 'Celsius'); #2
INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, '28 AD E7 6E 6 0 0 D7', NULL, 'Temp3', 'DS18B20', 'Celsius'); #3
INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, '28 83 99 6F 6 0 0 32', NULL, 'Temp4', 'DS18B20', 'Celsius'); #4
INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, '28 A7 6A 6F 6 0 0 B0', NULL, 'Temp5', 'DS18B20', 'Celsius'); #5

INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, 'Digital10', 'Temperature', 'Temp', 'DHT', 'Celsius');      #6
INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, 'Digital10', 'Humidity', 'Humidity', 'DHT', '%');               #7
INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, 'Digital10', 'HeatIndex', 'Heat Index', 'DHT', 'Celsius');       #8 

INSERT INTO sensor (deviceId, sensorAddress, sensorSubAddress, sensorName, sensorType, sensorUnit) VALUES (1, 'Analog0', NULL, 'Light', 'photocell', 'Luminas'); #9

#Table: sensorLog
CREATE  TABLE `sensorLog` (
  `logId` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
  `logDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `jobId`  INT NOT NULL COMMENT 'Job that triggered this log entry',  
  PRIMARY KEY (`logId`)
) ENGINE = InnoDB AUTO_INCREMENT=1;

#Index: idx_sensorLog
CREATE INDEX idx_sensorLog ON sensorLog ( 
    logDate 
);

#Table: sensorValue
CREATE  TABLE `sensorValue` (
  `sensorValueId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `logId` INT UNSIGNED NOT NULL,
  `sensorId` INT UNSIGNED NOT NULL ,
  `value` DECIMAL(6,2) NOT NULL ,  
  PRIMARY KEY (`sensorValueId`)
) ENGINE = InnoDB AUTO_INCREMENT=1;

#Index: idx_sensorValue
CREATE INDEX idx_sensorValue ON sensorLog ( 
    logDate 
);

CREATE  TABLE `job` (
  `jobId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `jobDescription` VARCHAR(45) NULL ,
  PRIMARY KEY (`jobId`) 
  )ENGINE = InnoDB AUTO_INCREMENT=1;

INSERT INTO job (jobId, jobDescription) VALUES (1, 'manual');
INSERT INTO job (jobId, jobDescription) VALUES (2, 'web');
INSERT INTO job (jobId, jobDescription) VALUES (3, 'startup scheduled');
INSERT INTO job (jobId, jobDescription) VALUES (4, '1 minute scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (5, '10 minute scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (6, '1 hour scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (7, '24 hour scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (8, '1 month scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (9, '6 month scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (10, '1 year scheduled ');
INSERT INTO job (jobId, jobDescription) VALUES (11, 'Unit testing');



CREATE VIEW dailyLog AS
SELECT l.logId, j.jobId, j.jobDescription, l.logDate,  s.sensorType, s.sensorName, v.value, s.sensorUnit
FROM arduino.sensorValue v, arduino.sensor s, arduino.sensorLog l, arduino.job j
where v.sensorId = s.sensorId and v.logId = l.logid and l.jobId = j.jobId
order by l.logDate;






