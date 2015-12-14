


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
DeviceAddress tempSensors[TemperatureDevices];  //DeviceAddress is uint8_t[8]
bool tempSensorsReadable[TemperatureDevices];

/* ############################### boot ########################### */
#define BOOTPIN 7        // what pin we're connected to DIGITAL BOOT (default closed: HIGH)

/* ############################### boot ########################### */
#define VEGGIELIGHT 6        // what pin we're connected to VEGGIE LIGHT  (default closed: HIGH)
#define LIGHT 5              // what pin we're connected to GreenhouseLight  (default closed: HIGH)

/* ##################### VARS ##################################### */
// All request will be transfered here
float TempVar[4];
int i;
char errorString[50];
char tempString[100];
char temp;
//char sensorFloatValue[10];
char sensorValue[5];
Process p;
String command;
 
void setup() {
// Bridge startup: 
  // do nothing for 10 seconds (do not interupt booting process):
  delay(10000); 

  pinMode(BOOTPIN, OUTPUT);
  digitalWrite(BOOTPIN, HIGH);

  pinMode(VEGGIELIGHT, OUTPUT);
  digitalWrite(VEGGIELIGHT, HIGH);

  pinMode(LIGHT, OUTPUT);
  digitalWrite(LIGHT, HIGH);
  
  pinMode(13,OUTPUT);
  digitalWrite(13, LOW);

  Bridge.begin();

  dht.begin();
  
  sensors.begin();
  SensorsSetUp();
  
  //asks for credentials:
  server.noListenOnLocalhost(); 
  //does not ask for credentials:
  //server.listenOnLocalhost();
  
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
        getCommand();
        
	clientSendJSON();

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
	else if (command == "veggielightoff") {
		VeggieLight(0);
	}
	else if (command == "veggielighton") {
		VeggieLight(1);
	}
	else if (command == "lightoff") {
		Light(0);
	}
	else if (command == "lighton") {
		Light(1);
	}
	else if (command == "boot") {
		Reboot();
	}
	else if (command == "data") {
		ReadData();
	}else{
          client.println("{\"error\":\"Invalid command\" }");
        }

    // Close connection and free resources.
    client.stop();
    
  }
  digitalWrite(13, HIGH);
  delay(50); // Poll every 50ms
       
}

void ReadData(){
	client.print("{ \"Light\": ");
	ReadLight();
	client.print(", \"DHT\": ");
	ReadDHT();
	client.print(", \"Temperatures\": ");
	ReadTemps();
        
//        client.print(", \"Command\": ");
//        client.print("\"");
//        client.print(command);
//        client.print("\"");

	client.print("}");
}

void ReadLight(){

	// Measure light level
	client.print("{ \"Light\":");
	client.print(analogRead(A0));
	client.print(", \"Address\": \"Analog0\"");
	client.print(" }");
}

void ReadDHT(){

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
		client.print(", \"Address\": \"Digital10\"");
		client.print("}");

	}
}

void ReadTemps(){

	sensors.requestTemperatures();
	client.print("[");
	for (i = 0; i < numberOfDevices; i++)
	{		
		client.print("{ \"Address\": \"");
		printAddress(tempSensors[i]);
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
	client.print("]");
}

void ShowLastError(){

	client.print("{ \"error\" : \"");
	client.print(errorString);
	client.print("\"}");

}

void Reboot(){
  digitalWrite(BOOTPIN, LOW); //send reboot signal
}

void VeggieLight(uint8_t onoff){
	if (onoff == 0){
		digitalWrite(VEGGIELIGHT, LOW);
	}
	else {
		digitalWrite(VEGGIELIGHT, HIGH);
	}
	client.print("{ \"result\" : \"success\" }");
}

void Light(uint8_t onoff){
	if (onoff == 0){
		digitalWrite(LIGHT, LOW);
	}
	else {
		digitalWrite(LIGHT, HIGH);
	}
	client.print("{ \"result\" : \"success\" }");
}

void clientSendJSON(){
	client.println("Status: 200");
	client.println("Content-type: application/json");
	client.println();
}

void getCommand(){
        //String command = client.readString();
        int i = 0;
        while(command.indexOf("GET") < 0 || i > 20 ){
                command = client.readStringUntil('\r');
                i++;
        }
        command.trim();        //kill whitespace
        command = command.substring(command.indexOf(" /")+2,command.indexOf(" HTTP"));  //"GET /data HTTP/1.1"
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

void printAddress(uint8_t* address){

	for (int j = 0; j < 8; j++)
	{
		client.print(address[j], HEX);
		if(j<7) client.print(" ");
	}

}
