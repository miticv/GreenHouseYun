python /mnt/sda1/arduino/mysqlLogDetail.py '1 month DB maintenance'


#delete task logger data older than a month?


#here perhaps delete log data of 10 min for older logs than 5 years and heep 1h logs only?
#10min logs per year: 52,560 records
#1h    logs per year: 8,760 records



python /mnt/sda1/arduino/mysqlLogDetail.py '- backing up database'
mysqldump -u root -p --create-options --routines --triggers arduino > ./db.dmp
#Copy backup somewhere?


# note these cause LOCKS, so be careful on your production server!
python /mnt/sda1/arduino/mysqlLogDetail.py '- check for integrity errors'
$ mysqlcheck -u root -p --check --databases arduino

python /mnt/sda1/arduino/mysqlLogDetail.py '- defragment'
$ mysqlcheck -u root -p --optimize --databases arduino

python /mnt/sda1/arduino/mysqlLogDetail.py '- rebuild indexes'
$ mysqlcheck -u root -p --analyze --databases arduino