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

##Arduino APIs:
It is accepting only calls from Linux box on: http://127.0.0.1:5555/

* temp  (reads temperature from DHT sensor)
* temps (reads all temperatures from sensors)
* light (reads light sensor)
* err (show last error)
* veggielightoff 
* veggielighton
* lightoff
* lighton
* boot (reboots the arduino)
* data (gets all sensor data back)


##APIs:

accepts "from" and "to"

* http://192.168.1.35/sd/api.php?action=get_info_log
* http://192.168.1.35/sd/api.php?action=get_error_log

accepts "from", "to" and "freq"

* http://192.168.1.35/sd/api.php?action=get_sensor_log
* 

* http://192.168.1.35/sd/api.php?action=light_off
* http://192.168.1.35/sd/api.php?action=light_on
* http://192.168.1.35/sd/api.php?action=get_uptime
* http://192.168.1.35/sd/api.php?action=get_sensor_data
* http://192.168.1.35/sd/api.php?action=get_sensor_definitions
* http://192.168.1.35/sd/api.php?action=get_sensor_and_uptime_data
* http://192.168.1.35/sd/api.php?action=reset_arduino
* http://192.168.1.35/sd/api.php?action=reset_yun


##BASIC WRT


* ssh root@ip.address.of.the.yun
* mysql -root -p
