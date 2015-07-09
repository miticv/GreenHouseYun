python /mnt/sda1/arduino/python/loggerInfo.py "1 month DB maintenance Started"
python /mnt/sda1/arduino/python/dbBackup.py 
#delete task logger data older than a month?
#
#here perhaps delete log data of 10 min for older logs than 5 years and heep 1h logs only?
#10min logs per year: 52,560 records
#1h    logs per year: 8,760 records
#
#To rebuild all the tables in a single database, specify the database name without any following table name:
#shell> mysqldump db_name > dump.sql
#shell> mysql db_name < dump.sql
# note these cause LOCKS, so be careful on your production server!
#
#python /mnt/sda1/arduino/mysqlLogDetail.py "- check for integrity errors"
#myisamchk -s /srv/mysql/arduino/*.MYI
#$ mysqlcheck -u root -p --check --databases arduino
#
#python /mnt/sda1/arduino/mysqlLogDetail.py "- defragment"
# do it for all tables:
#ALTER TABLE tbl_name ENGINE=INNODB
#other way is to dump table using mysqldump, drop table and reload it from the file.
#
#myisamchk -r /srv/mysql/arduino/*.MYI
#$ mysqlcheck -u root -p --optimize --databases arduino
#python /mnt/sda1/arduino/mysqlLogDetail.py "- rebuild indexes"
#$ mysqlcheck -u root -p --analyze --databases arduino