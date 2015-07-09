#python /mnt/sda1/arduino/mysqlLogDetail.py '24 hours - scheduled maintnance'
python /mnt/sda1/arduino/python/loggerInfo.py "24h scheduled maintenance"
#
#reset-mcu    #reset arduino like this
#
#this command is not booting up linux properly. Have to dissconnect and connect power to reboot!
#/sbin/reboot or just reboot
#
#following equivalent to reset power button - also not working! :(
#echo 1 > /proc/sys/kernel/sysrq 
#echo b > /proc/sysrq-trigger