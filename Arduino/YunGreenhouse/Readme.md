Ino is using following pins:
##ANALOG:
* 0 - read light

##DIGITAL:
* 2 - One Wire (for temperature sensors)
* 7 - Pin to reboot the Yun
* 10 - DHT Temp/Humidity Sensor (DHT22)
* 13 - built in red light on the YUN (signal when arduino is ready)



##Website: 
* http://192.168.1.35/sd/

##APIs:

accepts "from" and "to"

* http://192.168.1.35/sd/api.php?action=get_info_log
* http://192.168.1.35/sd/api.php?action=get_error_log

accepts "from", "to" and "freq"

* http://192.168.1.35/sd/api.php?action=get_sensor_log


* http://192.168.1.35/sd/api.php?action=get_uptime
* http://192.168.1.35/sd/api.php?action=get_sensor_data
* http://192.168.1.35/sd/api.php?action=get_sensor_definitions
* http://192.168.1.35/sd/api.php?action=get_sensor_and_uptime_data
* http://192.168.1.35/sd/api.php?action=reset_arduino
* http://192.168.1.35/sd/api.php?action=reset_yun



##BASIC WRT


* ssh root@ip.address.of.the.yun
* mysql -root -p
