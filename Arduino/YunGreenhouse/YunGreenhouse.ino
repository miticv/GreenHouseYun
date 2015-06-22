


#include <Bridge.h>
#include <YunServer.h>
#include <YunClient.h> 
#include <Process.h>

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
	client.print("{temp:\"List temperature and humidity\",");
	client.print("temps:\"List temperatures\",");
	client.print("light:\"Measure light\",");
	client.print("date:\"Show current device date time\",");
	client.print("err:\"Show last error\",");
	client.print("reset:\"Reset Arduino\"");
	client.print("}");
}

void ReadDate() {

	p.runShellCommand("date +\"%Y-%m-%d %H:%M:%S\"");
	while (p.running());

	while (p.available()>0) {
		client.print((char)p.read());
	}
	client.flush();

}

void ReadLight(){

	// Measure light level
	client.print("{ light:");
	client.print(analogRead(A0));
	client.print(" }");
}

void ReadDHT(){
	digitalWrite(13, LOW);
	// Reading temperature or humidity takes about 250 milliseconds!
	// Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
	TempVar[0] = dht.readHumidity();
	// Read temperature as Celsius
	TempVar[1] = dht.readTemperature();
	// Read temperature as Fahrenheit
	TempVar[2] = dht.readTemperature(true);
	// Check if any reads failed and exit early (to try again).
	if (isnan(TempVar[0]) || isnan(TempVar[1]) || isnan(TempVar[2])) {
		//Serial.println("Failed to read from DHT sensor!");
		client.print("{error:\"Failed to read from DHT sensor!\"}");
	}
	else{
		// Compute heat index
		// Must send in temp in Fahrenheit!
		TempVar[3] = dht.computeHeatIndex(TempVar[2], TempVar[0]);

		client.print("{ Humidity%:");
		client.print(TempVar[0]);
		client.print(" , TempC:");
		client.print(TempVar[1]);
		client.print(" , TempF:");
		client.print(TempVar[2]);
		client.print(" , HeatIndexF:");
		client.print(TempVar[3]);

		client.print("}");

	}
}

void ReadTemps(){

	sensors.requestTemperatures();
	client.print("{");
	for (i = 0; i < numberOfDevices; i++)
	{
		client.print("Temp");
		client.print(i + 1);
		client.print(": {");
		client.print("Address: \"");
		client.print((int)tempSensors[i], HEX);
		//showAddress(tempSensors[i]);
		//for (uint8_t k = 0; k < 8; i++)
		//{
		//	// zero pad the address if necessary
		//	if ((uint8_t)tempSensors[i][k] < 16) client.print("0");
		//	client.print((uint8_t)tempSensors[i][k], HEX);
		//}

		client.print("\" ,");
		client.print("TempC: ");		
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
	client.print("{error: \"");
	client.print(errorString);
	client.print("\"}");

}

void ResetArduino(){

	client.print("{ resettingArduino: \"Commited\" }");
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

// function to print a device address
//void showAddress(uint8_t deviceAddress[])
//{
//		for (uint8_t k = 0; k < 8; i++)
//		{
//			// zero pad the address if necessary
//			if (deviceAddress[k] < 16) client.print("0");
//			client.print(deviceAddress[k], HEX);
//		}
//}
