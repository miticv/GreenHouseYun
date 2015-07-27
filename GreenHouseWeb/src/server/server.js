/* jshint node:true */
/* jshint -W101 */
'use strict';
//
var http = require('http');
var express = require('express');
var app = express();

var port = process.env.PORT || 1337;
var environment = process.env.NODE_ENV || 'dev';
process.env.NODE_ENV = environment;
/* jshint ignore:start */
var apiJson = {
    'result' : {
        'sensors' : {
            'Light': { 'Light': 1016, 'Address': 'Analog0' },
            'DHT': { 'TempC': 20.4, 'HumidityPercent': 48.4, 'HeatIndexF': 77.24, 'TempF': 68.72, 'Address': 'Digital10' },
            'Temperatures': [{ 'TempC': 23.06, 'Address': '28 39 E8 6D 6 0 0 5D' }, { 'TempC': 19.37, 'Address': '28 35 2 70 6 0 0 E2' }, { 'TempC': 20.31, 'Address': '28 AD E7 6E 6 0 0 D7' }, { 'TempC': 19.0, 'Address': '28 83 99 6F 6 0 0 32' }, { 'TempC': 20.69, 'Address': '28 A7 6A 6F 6 0 0 B0' }]
        },
        'uptime': { 'alive': { 'secAlive': '691902', 'aliveFor': '8 days, 0 hours, 11 minutes and 42 seconds', 'uptime': '8 days, 11 min' }, 'loadAverage': { 'min1': '0.04', 'min5': '0.10', 'min15': '0.13', 'Description': '0 is idle, 1 is fully utilized, 1.05 means 5% of processes waited for their turn.' }, 'deviceTime': { 'dateTime': '2015-07-24 14:59:26' } },
        'sensorLabels' : { 'sensors': [{ 'type': 'DS18B20', 'subAddress': null, 'id': 1, 'name': 'Temp1', 'address': '28 39 E8 6D 6 0 0 5D' }, { 'type': 'DS18B20', 'subAddress': null, 'id': 2, 'name': 'Temp2', 'address': '28 35 2 70 6 0 0 E2' }, { 'type': 'DS18B20', 'subAddress': null, 'id': 3, 'name': 'Temp3', 'address': '28 AD E7 6E 6 0 0 D7' }, { 'type': 'DS18B20', 'subAddress': null, 'id': 4, 'name': 'Temp4', 'address': '28 83 99 6F 6 0 0 32' }, { 'type': 'DS18B20', 'subAddress': null, 'id': 5, 'name': 'Temp5', 'address': '28 A7 6A 6F 6 0 0 B0' }, { 'type': 'DHT', 'subAddress': 'Temperature', 'id': 6, 'name': 'Temp', 'address': 'Digital10' }, { 'type': 'DHT', 'subAddress': 'Humidity', 'id': 7, 'name': 'Humidity', 'address': 'Digital10' }, { 'type': 'DHT', 'subAddress': 'HeatIndex', 'id': 8, 'name': 'Heat Index', 'address': 'Digital10' }, { 'type': 'photocell', 'subAddress': null, 'id': 9, 'name': 'Light', 'address': 'Analog0' }] }
    }
};
/* jshint ignore:end */

//logger
app.all('*', function (req, res, next) {
    console.log(req.method + ' ' + req.path);
    next(); // Passing the request to the next handler in the stack.
});

switch (environment) {
    case 'build':
        console.log('*** BUILD ***');
        app.use(express.static(__dirname + '/../../build'));
        app.use('/index.html', express.static(__dirname + '/../../build/index.html'));
        app.get('/sd/api.php', function (req, res) {
            res.set('Content-Type', 'application/json');
            res.json(apiJson);
            res.send();
        });
        break;
    case 'dev':
        console.log('*** DEV ***');
        //****************static resources
        app.use('/index.html', express.static(__dirname + '/../client/index.html'));
        app.use(express.static(__dirname + '/../client'));
        app.get('/sd/api.php', function (req, res) {
            res.set('Content-Type', 'application/json');
            res.json(apiJson);
            res.send();
        });
        break;
}
//*****************Setup View Engine vash:
//var controllers = require('./controllers');
//app.set('views', __dirname + '/views');
//app.set('view engine', 'vash');
//controllers.init(app);

var server = http.createServer(app);

server.listen(port, function () {
    console.log('Express server listening on port ' + port);
    console.log('cwd = ' + process.cwd());
    console.log('env = ' + environment);
});