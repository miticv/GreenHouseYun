/* jshint node:true */
/* jshint -W101 */
'use strict';
//
var http = require('http');
var express = require('express');
var fs = require('fs');
var app = express();

var port = process.env.PORT || 1337;
var environment = process.env.NODE_ENV || 'dev';
process.env.NODE_ENV = environment;
/* jshint ignore:start */

var apiJson = JSON.parse(fs.readFileSync(__dirname + '/../server/data/apiJson.json', 'utf8'));
var apiHistoryJson = JSON.parse(fs.readFileSync(__dirname + '/../server/data/apiHistoryJson.json', 'utf8'));
var apiDefinitionsJson = JSON.parse(fs.readFileSync(__dirname + '/../server/data/apiDefinitionsJson.json', 'utf8'));
var apiErrorJson = { "result": { "error": "unknown request" } };
/* jshint ignore:end */

//logger
app.all('*', function (req, res, next) {
    console.log(req.method + ' ' + req.path);
    //console.log(req.query);

    next(); // Passing the request to the next handler in the stack.
});

switch (environment) {
    case 'build':
        console.log('*** BUILD ***');

        app.get('/sd/api.php', function (req, res, next) {
            res.set('Content-Type', 'application/json');
            if (req.query.action == 'get_sensor_log') {
                res.json(apiHistoryJson);
            }else if (req.query.action == 'get_sensor_and_uptime_data') {
                res.json(apiJson);
            }else if (req.query.action == 'get_sensor_definitions') {
                res.json(apiDefinitionsJson);
            } else {
                res.json(apiErrorJson);
            }
            next();
        });

        app.use('/favicon.ico', express.static(__dirname + '/../server/favicon.ico'));
        app.use('/sd/index.html', express.static(__dirname + '/../../build/index.html'));
        app.use(express.static(__dirname + '/../../build'));

        break;
    case 'dev':
        console.log('*** DEV ***');
                       
        //app.use('/sd/api.php',   express.static(__dirname + '/../server/data/apiJson.json'));
        app.get('/sd/api.php', function (req, res, next) {
            res.set('Content-Type', 'application/json');
            if (req.query.action == 'get_sensor_log') {                
                res.json(apiHistoryJson);
            }else if (req.query.action == 'get_sensor_and_uptime_data') {                
                res.json(apiJson);
            }else if (req.query.action == 'get_sensor_definitions') {
                res.json(apiDefinitionsJson);
            } else {
                res.json(apiErrorJson);
            }
            next();
        });

        app.use('/favicon.ico', express.static(__dirname + '/../server/favicon.ico'));
        app.use('/sd/index.html', express.static(__dirname + '/../client/index.html'));
        app.use('/sd', express.static(__dirname + '/../client'));
        app.use('/temp', express.static(__dirname + '/../../temp'));
        app.use('/bower_components', express.static(__dirname + '/../../bower_components'));
        app.use('/src/client/app', express.static(__dirname + '/../client/app'));
        app.use(express.static(__dirname + '/../client'));
        
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