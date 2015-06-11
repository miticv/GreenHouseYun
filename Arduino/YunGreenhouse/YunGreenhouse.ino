// Example testing sketch for various DHT humidity/temperature sensors
// Written by ladyada, public domain

#include "DHT.h"
#include <Bridge.h>
#include <YunServer.h>
#include <YunClient.h> 

#include <OneWire.h>
#include <DallasTemperature.h>

#define DHTPIN 2     // what pin we're connected to
#define busPin 10
OneWire bus(busPin);
DallasTemperature sensors(&bus);
DeviceAddress sensor1;
DeviceAddress sensor2;

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


// All request will be transfered here
YunServer server;
String startString;
 
void setup() {
// Bridge startup
  pinMode(13,OUTPUT);
  digitalWrite(13, LOW);
  Bridge.begin();
  digitalWrite(13, HIGH);
  
  dht.begin();
  
  server.listenOnLocalhost();
  server.begin();
  
  
    if (!sensors.getAddress(sensor1, 0)) 
  {
    //Serial.println("DS18B20 NUMBER 1 NOT FOUND!");
  }
  if (!sensors.getAddress(sensor2, 1)) 
  {
    //Serial.println("DS18B20 NUMBER 2 NOT FOUND!");
  }
}

void loop() {
  
  // Get clients coming from server
  YunClient client = server.accept();
  // There is a new client?
  if (client) {
    // read the command
    String command = client.readString();
    command.trim();        //kill whitespace
    // is "temperature" command?
    if (command == "temp") {
      sensors.requestTemperatures();
  float tempC1 = sensors.getTempC(sensor1);
  float tempC2 = sensors.getTempC(sensor2);
  
      // Reading temperature or humidity takes about 250 milliseconds!
      // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
      float h = dht.readHumidity();
      // Read temperature as Celsius
      float t = dht.readTemperature();
      // Read temperature as Fahrenheit
      float f = dht.readTemperature(true);
           // Check if any reads failed and exit early (to try again).
      if (isnan(h) || isnan(t) || isnan(f)) {
        //Serial.println("Failed to read from DHT sensor!");
        client.print("{error:\"Failed to read from DHT sensor!\"}");
        return;
      }
      // Compute heat index
      // Must send in temp in Fahrenheit!
      float hi = dht.computeHeatIndex(f, h);
  
      client.print("{Humidity%:");
      client.print(h);
      client.print(" , TempC:");
      client.print(t);
      client.print(" , TempF:");
      client.print(f);
      client.print(" , HeatIndexF:");
      client.print(hi);      
	  
	  client.print(" , Temp1C:");
	  client.print(tempC1);
	  client.print(" , Temp2C:");
	  client.print(tempC2);
	  
      client.print("}");
      
    }
    // Close connection and free resources.
    client.stop();
    
  }
      
  delay(50); // Poll every 50ms
       
}
