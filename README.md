# GreenHouseYun
Green House Controller App using Arduino Yun

Command for getting public IP from network interface eth1:
```bash
LANG=c ifconfig eth1 | grep "inet addr" | awk -F: '{print $2}' | awk '{print $1}'
```
or if not sure what is the interface, list all interfaces:
```bash
LANG=c ifconfig |grep -B1 "inet addr" |awk '{ if ( $1 == "inet" ) { print $2 } else if ( $2 == "Link" ) { printf "%s:" ,$1 } }' |awk -F: '{ print $1 ": " $3 }'
```


