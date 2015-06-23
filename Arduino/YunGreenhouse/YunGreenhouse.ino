


#include <Bridge.h>
#include <YunServer.h>
#include <YunClient.h> 
#include <Process.h>  /* for making OpenWrt linux process requests */

YunServer server;
YunClient client;

/* ##################### temperature humidity ##################### */
#include "DHT.h"
#define DHTPIN 10        // what pin we're connected to DIGITAL


// Uncomment whatever type you're using!
//#define DHTTYPE DHT11   // DHT 11 
#define DHTTYPE DHT22   // DHT 22  (AM2302)
//#define DHTTYPE DHT21   // DHT 21 (AM2301)

// Connect pin 1 (on the left) of the sensor to +5V
// NOTE: If using a board with 3.3V logic like an Arduino Due connect pin 1
// to 3.3V instead of 5V!
// Connect pin 2 of the sensor to whatever your DHTPIN is
// Connect pin 4 (on the right) of the sensor to GROUND
// Connect a 10K resistor from pin 2 (data) to pin 1 (power) of the sensor

// Initialize DHT sensor for normal 16mhz Arduino
DHT dht(DHTPIN, DHTTYPE);
// NOTE: For working with a faster chip, like an Arduino Due or Teensy, you
// might need to increase the threshold for cycle counts considered a 1 or 0.
// You can do this by passing a 3rd parameter for this threshold.  It's a bit
// of fiddling to find the right value, but in general the faster the CPU the
// higher the value.  The default for a 16mhz AVR is a value of 6.  For an
// Arduino Due that runs at 84mhz a value of 30 works.
// Example to initialize DHT sensor for Arduino Due:
//DHT dht(DHTPIN, DHTTYPE, 30);

/* ##################### resetFunc ################################ */
void(*resetFunc) (void) = 0;  //declare reset function @ address 0


/* ##################### temperature libraries ##################### */
#include <OneWire.h>
#include <DallasTemperature.h>

#define ONEWIREPIN 2  // what pin we're connected to DIGITAL
OneWire bus(ONEWIREPIN);
DallasTemperature sensors(&bus);
const int TemperatureDevices = 10;
int numberOfDevices;
DeviceAddress tempSensors[TemperatureDevices];
bool tempSensorsReadable[TemperatureDevices];

/* ##################### VARS ##################################### */
// All request will be transfered here
float TempVar[4];
int i;
char errorString[50];
char tempString[100];
char tempString2[100];
char temp;
//char sensorFloatValue[10];
char sensorValue[5];
Process p;

 
void setup() {
// Bridge startup: 
  pinMode(13,OUTPUT);
  digitalWrite(13, LOW);

  Bridge.begin();

  dht.begin();
  
  sensors.begin();
  SensorsSetUp();
  
  server.listenOnLocalhost();
  server.begin();
  
  //light up LED 13 as a signal that bridge is ready
  digitalWrite(13, HIGH);
 
}

void loop() {
  
  // Get clients coming from server
  client = server.accept();
  // There is a new client?
  if (client) {
    // read the command
    String command = client.readString();
    command.trim();        //kill whitespace

    if (command == "temp") {
		ReadDHT();             
    }
	else if (command == "temps") {
		ReadTemps();
	}
	else if (command == "light") {
		ReadLight();
	}
	else if (command == "err") {
		ShowLastError();
	}
	else if (command == "reset") {
		ResetArduino();
	}
	else if (command == "date") {
		ReadDate();
	}
	else if (command == "alivesince") {
		SecondsSinceLastReboot();
	}
	else if (command.indexOf("/takepic/") > 0) {
		//strcpy(tempString, "pic_");
		//strcat(tempString, command[command.indexOf("/takepic/") + 9]);
		TakePicture("test123.jpg");
	}
	else if (command == "sendssm") {
		SendTextMessage("Flood warning test", false);
	}
	else if (command == "sendmsm") {
		SendTextMessage("Flood warning test!", true);
	}
	else{
		ShowCommands();
	}

    // Close connection and free resources.
    client.stop();
    
  }
  digitalWrite(13, HIGH);
  delay(50); // Poll every 50ms
       
}


void ShowCommands(){

	clientSendJSON();
	client.print("{\"temp\":\"List temperature and humidity\",");
	client.print("\"temps\":\"List temperatures\",");
	client.print("\"light\":\"Measure light\",");
	client.print("\"date\":\"Show current device date time\",");
	client.print("\"alivesince\":\"Seconds since last Linux reboot\",");
	client.print("\"takepic\":\"Take a pic\",");
	client.print("\"sendssm\":\"Send message to phone\",");
	client.print("\"sendmsm\":\"Send message to phone with picture\",");			
	client.print("\"err\":\"Show last error\",");
	client.print("\"reset\":\"Reset Arduino\"");
	client.print("}");
}

void SecondsSinceLastReboot(){
	clientSendJSON();
	client.print("{ \"SecondsSinceLastReboot\":\"");
	printShellCommand("(</proc/uptime awk '{print $1}')");
	client.print("\" }");
}

void ReadDate() {
	clientSendJSON();
	client.print("{ \"DateTime\":\"");
	printShellCommand("date +\"%Y-%m-%d %H:%M:%S\"");
	client.print("\" }");
}

void ReadLight(){
	clientSendJSON();
	// Measure light level
	client.print("{ \"light\":");
	client.print(analogRead(A0));
	client.print(" }");
}

void ReadDHT(){
	clientSendJSON();
	// Reading temperature or humidity takes about 250 milliseconds!
	// Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
	TempVar[0] = dht.readHumidity();
	// Read temperature as Celsius
	TempVar[1] = dht.readTemperature();
	// Read temperature as Fahrenheit
	TempVar[2] = dht.readTemperature(true);
	// Check if any reads failed and exit early (to try again).
	if (isnan(TempVar[0]) || isnan(TempVar[1]) || isnan(TempVar[2])) {
		client.print("{\"error\":\"Failed to read from DHT sensor!\"}");
	}
	else{
		// Compute heat index
		// Must send in temp in Fahrenheit!
		TempVar[3] = dht.computeHeatIndex(TempVar[2], TempVar[0]);

		client.print("{ \"HumidityPercent\":");
		client.print(TempVar[0]);
		client.print(", \"TempC\":");
		client.print(TempVar[1]);
		client.print(", \"TempF\":");
		client.print(TempVar[2]);
		client.print(" , \"HeatIndexF\":");
		client.print(TempVar[3]);
		client.print("}");

	}
}

void ReadTemps(){
	clientSendJSON();
	sensors.requestTemperatures();
	client.print("{");
	for (i = 0; i < numberOfDevices; i++)
	{
		client.print("\"Temp");
		client.print(i + 1);
		client.print("\": {");
		client.print("\"Address\": \"");
		client.print((int)tempSensors[i], HEX);
		client.print("\" ,");
		client.print("\"TempC\": ");		
		if (tempSensorsReadable[i]){ 
			TempVar[0] = sensors.getTempC(tempSensors[i]);
			if (TempVar[0] < -100){  //sensor disconnected shows -127
				client.print("-999");
			}
			else{
				client.print(TempVar[0]);
			}			
		}
		else {
			client.print("-999");
		}
		client.print("}");
		if (i != (numberOfDevices - 1)) client.print(", ");
	}
	client.print("}");
}

void ShowLastError(){
	clientSendJSON();
	client.print("{ \"error\" : \"");
	client.print(errorString);
	client.print("\"}");

}

void ResetArduino(){
	clientSendJSON();
	client.print("{ \"resettingArduino\" : \"Commited\" }");
	resetFunc();
}

void SensorsSetUp(){

	numberOfDevices = sensors.getDeviceCount();

	for (i = 0; i < numberOfDevices; i++)
	{
        strcpy(errorString, "DS18B20 not found: ");
		if (!sensors.getAddress(tempSensors[i], i))
		{
			tempSensorsReadable[i] = false;
			strcat(errorString, itoa(i + 1, sensorValue, 10));
			strcat(errorString, " ");
		}
		else{
			tempSensorsReadable[i] = true;
		}
	}

  
}

void printShellCommand(char* str){
	p.runShellCommand(str);
	while (p.running());

	while (p.available()>0) {
		temp = (char)p.read();
		if(temp != '\n') client.print(temp);
	}
	client.flush();

}

char* getTimeStamp(){
	strcpy(tempString, "\"");
	p.runShellCommand("date \"+%m%d%y%H%M%S\"");
	while (p.running());

	while (p.available()>0) {
		temp = (char)p.read();
		if (temp != '\n') strcat(tempString, &temp);
	}
	return tempString;
}

void SendTextMessage(char* textMessage, bool withPicture){
	
	if (withPicture){
		strcat(tempString2, getTimeStamp());
		
		strcpy(tempString, "\"/mnt/sda1/arduino/www/images/temp");
		strcpy(tempString, tempString2);
		strcpy(tempString, ".jpg\"");

		TakePicture(tempString); // http://miticv.duckdns.org:82/sd/images/tempmdyHMS.jpg
	}
	
	strcpy(tempString, "\""); 
	strcat(tempString, textMessage);
	strcat(tempString, "\"");

	p.begin("python"); // Process that launch the "python" command
	if (withPicture){
		p.addParameter("/mnt/sda1/arduino/send-mms.py"); // Add the path parameter
		p.addParameter(tempString); // The message body
		p.addParameter("http://miticv.duckdns.org:82/sd/images/temp.jpg"); // The message body
	}
	else{
		p.addParameter("/mnt/sda1/arduino/send-sms.py"); // Add the path parameter
		p.addParameter(tempString); // The message body	
	}
	p.run(); // Run the process and wait for its termination
	while (p.running());

	while (p.available()>0) {
		client.print((char)p.read());
	}
	if (withPicture){
		client.print("{ \"message\": \"sent with picture\", \"picture\": \"http://miticv.duckdns.org:82/sd/images/temp.jpg\" }");
	}
	else{
		client.print("{ \"message\": \"sent\" }");
	}
	
}

void TakePicture(char* picName) {
	//fswebcam /mnt/sda1/picName.jpg -r 640x480
	//fswebcam /mnt/sda1/arduino/www/picName.jpg -r 640x480		

	p.begin("fswebcam");
	p.addParameter(picName);
	p.addParameter("-r 640x480");
	p.run();
	while (p.running());

	while (p.available()>0) {
		client.print((char)p.read());
	}	
}

void clientSendJSON(){
	client.println("Status: 200");
	client.println("Content-type: application/json");
	client.println();
}