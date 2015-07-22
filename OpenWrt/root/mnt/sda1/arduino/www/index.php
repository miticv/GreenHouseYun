<?php

// Add links to show error and info log!
// Add graph showing history data
// Do more error logging
//DONE: Use Database sensor name to show on the api, so we can use that DB name to show on front end
?>
<!DOCTYPE html>
<html> 
<!--<html manifest="cache.app1"> -->
<head>
  <title>Green House</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.0.0/css/bootstrap-datetimepicker.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/angular-busy/4.1.2/angular-busy.min.css" />
  <link rel="stylesheet" href="/sd/externals/angular-chart.css" />
  

  <style type="text/css">
    textarea#fileContent {
      width: 100%;
      height: 200px;
      -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
      -moz-box-sizing: border-box; /* Firefox, other Gecko */
      box-sizing: border-box; /* Opera/IE 8+ */
    }
    .green { color: green; }
    .yellow { color: yellow; }
    .red { color: #a94442; }
    input.error:focus { box-shadow: 0 0 1px 1px red !important; }
    .tdrefresh { cursor:pointer; }
    table.table>tbody>tr>td.tdrefresh:hover { background-color: #a4d091 !important; }

    /* page link */
    div[ng-view].ng-enter {
      -webkit-animation: fadeInRight 0.5s;
      animation: fadeInRight 0.5s;
    }

    /* ng-hide and ng-show */
    .ng-hide-remove {
      -webkit-animation: fadeIn 0.5s;
      animation: fadeIn 0.5s;
    }

    .ng-hide-add {
      -webkit-animation: fadeOut 0.5s;
      animation: fadeOut 0.5s;
      display: block !important;
    }


    /* ng-hide and ng-show */
    span.ng-hide-remove {
      -webkit-animation: flash 0.5s;
      animation: flash 0.5s;
    }

    span.ng-hide-add {
      -webkit-animation: none;
      animation: none;
      
    }

  </style>
</head>
<body>

  <div>

    <div data-ng-include="'header.html'"></div>

    <div class="container body-content ui-view-container" role="main" style="margin-bottom:50px;">
      <div ng-view data-ng-view-animate="zoomIn"></div>
    </div>

  </div>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.12/angular.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.12/angular-route.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.12/angular-animate.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular-messages.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.1/Chart.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.0.0/js/bootstrap-datetimepicker.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular-busy/4.1.2/angular-busy.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js"></script>
  
  <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls.min.js"></script>
  <script src="/sd/externals/angular-chart.min.js"></script>
  "chart.js"

  <script type="text/ng-template" id="header.html">
    <nav class="navbar navbar-default text-center">
      <div class="container">

        <div class="navbar-header text-center">
          <a class="navbar-brand" href="/#/"><i class="fa fa-leaf green"></i> Green House</a>
        </div>

        <ul class="nav navbar-nav navbar-right">
          <li><a ng-hide="layout.isLoggedIn" target="_blank" href="/#/login"><i class="fa fa-wrench"></i> admin</a></li>
        </ul>
      </div>
    </nav>
  </script>

  <script type="text/ng-template" id="home.html">


    <div cg-busy="loading">
      <div ng-show="loaded">
        <div class="row">
          <div class="col-sm-6 table-responsive">
            <table class="table">
              <caption>User Options</caption>
              <tbody>
                <tr class="success">
                  <td ng-click="load()"class="tdrefresh">

                    <i class="fa fa-refresh fa-lg green" style="position:relative;top:10px; "></i>
                    <span>{{time | amDateFormat:'MMMM Do YYYY, HH:mm:ss (dd)' }} </span> <small class="text-info"> ({{timeSinceText}} ago)</small><br />
                    <small style="margin-left:20px;">Click to refresh new data</small>

                  </td>
                  <td>
                    <div class="btn-group pull-right" role="group">
                      <span ng-click="tempFormat='C'" type="button" class="btn btn-default" ng-class="{ 'btn-primary active': tempFormat == 'C'}">C&deg;</span>
                      <span ng-click="tempFormat='F'" type="button" class="btn btn-default" ng-class="{ 'btn-primary active': tempFormat == 'F'}">F&deg;</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div><!-- /.col-sm-6 -->
          <div class="col-sm-6 table-responsive">
            <table class="table">
              <caption>System statistics</caption>
              <tbody>
                <tr class="success">
                  <td><i class="fa fa-heartbeat green"></i>  System running for <strong><span class="green">{{uptime}}</span></strong> since last reboot </td>
                </tr>
                <tr class="success">
                  <td><i class="fa fa-tachometer green"></i> System utilization <strong><span class="green">{{util}}</span></strong> </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div><!--/row-->

        <div class="row">
          <div class="col-sm-6 table-responsive">
            <table class="table">
              <caption>Air statistics</caption>
              <thead>
                <tr class="bg-primary"><td>Air</td><td> Out</td><td>In</td></tr>
              </thead>
              <tbody>
                <tr class="success">
                  <td>{{temperature.name}} <abbr data-popover-title="Device Address" data-popover="{{temperature.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td><span class="badge"><span ng-show="tempFormat == 'C'">{{temperature.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temperature.f}} F&deg;</span></span></td><td></td></tr>
                <tr class="success">
                  <td>{{humidityPercentage.name}}</td>
                  <td> <span class="badge"><span>{{humidityPercentage.percentage}} &#37;</span> </span></td><td><!--<span class="badge">80 &#37;</span>--></td></tr>
                <tr class="success">
                  <td>{{heatIndex.name}}</td>
                  <td> <span class="badge"><span ng-show="tempFormat == 'C'">{{heatIndex.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{heatIndex.f}} F&deg;</span> </span></td><td><!--<span class="badge">32 </span>--></td></tr>
                <tr class="success">
                  <td>{{light.name}} <abbr data-popover-title="Device Address" data-popover="{{light.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td> <span class="badge">{{light.v}}</span></td> <td></td></tr>
              </tbody>
            </table>
          </div><!-- /.col-sm-6 -->
          <div class="col-sm-6  table-responsive">
            <table class="table">
              <caption>Water Statistics</caption>
              <thead>
                <tr class="bg-primary"><td>Water</td><td></td></tr>
              </thead>
              <tbody>
                <!--<tr class="success"><td>Pool Level</td><td><span class="badge">5 m</span></td></tr>-->
                <tr class="success">
                  <td>{{temp1.name}} <abbr data-popover-title="Device Address" data-popover="{{temp1.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td><span class="badge"><span ng-show="tempFormat == 'C'">{{temp1.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temp1.f}} F&deg;</span></span></td></tr>
                <tr class="success">
                  <td>{{temp2.name}} <abbr data-popover-title="Device Address" data-popover="{{temp2.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td><span class="badge"><span ng-show="tempFormat == 'C'">{{temp2.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temp2.f}} F&deg;</span></span></td></tr>
                <tr class="success">
                  <td>{{temp3.name}} <abbr data-popover-title="Device Address" data-popover="{{temp3.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td><span class="badge"><span ng-show="tempFormat == 'C'">{{temp3.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temp3.f}} F&deg;</span></span></td></tr>
                <tr class="success">
                  <td>{{temp4.name}} <abbr data-popover-title="Device Address" data-popover="{{temp4.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td><span class="badge"><span ng-show="tempFormat == 'C'">{{temp4.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temp4.f}} F&deg;</span></span></td></tr>
                <tr class="success">
                  <td>{{temp5.name}} <abbr data-popover-title="Device Address" data-popover="{{temp5.address}}" data-popover-placement="right" data-popover-trigger="mouseenter"><i class="fa fa-map-marker"></i></abbr></td>
                  <td><span class="badge"><span ng-show="tempFormat == 'C'">{{temp5.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temp5.f}} F&deg;</span></span></td></tr>
                <!--<tr class="success"><td>Pump Running</td><td><i class="fa fa-cog fa-spin fa-lg green"></i></td></tr>-->
              </tbody>
            </table>

          </div><!-- /.col-sm-6 -->
        </div><!--/row-->

        <div class="row">

          <canvas id="myChart" width="400" height="400"></canvas>
        </div>


          </div><!--/row-->
        </div>
    </div>
    <div ng-hide="loaded">
      <div class="col-sm-12">
        Could not load data. Please <a href="" ng-click="load()">refresh</a>
      </div>
    </div>
  </script>


  <script id="routing">
    angular.module('greenhouse', ['ngRoute', 'ngAnimate', 'ui.bootstrap', 'cgBusy', 'angularMoment', 'ngMessages', 'chart.js']).
      config(['$routeProvider', function ($routeProvider) {
        $routeProvider.
         when("/", { templateUrl: "home.html", controller: "homeController" }).
         otherwise({ redirectTo: '/' });

      }])
  </script>
  <script id="service">
    angular.module('greenhouse').
      service("greenApiService", ['$http', '$q', function ($http, $q) {

        //var host = 'http://miticv.duckdns.org:82/arduino';
        var host = '';
        return ({

          isAdmin: isAdmin,

          getSensorData: getSensorData,
          getSensorStatus: getSensorStatus,

          doReboot: doReboot
        });
        //#endregion
        //#region isAdmin
        function isAdmin(pin) {

          return $http({
            method: "get",
            url: host + '/admin?U=' + pin
          });
        }
        //#endregion
        //#region doReboot
        function doReboot() {

          return $http({
            method: "put",
            headers: { 'PP': sessionStorage.getItem('pass') },
            url: host + '/sd/api.php?action=reset_arduino'
          });
        }
        //#endregion
        //#region getSensotData
        function getSensorData(from, to, frequency) {
          //DATA1503.JSN
          return $http({
            method: "get",
            url: host + '/sd/api.php?action=get_sensor_log&from=' + from + 'to=' + to + '&freq=' + frequency
          });
        }
        //#endregion
        //#region getSensotData
        function getInfoData(from, to) {
          //DATA1503.JSN
          return $http({
            method: "get",
            url: host + '/sd/api.php?action=get_info_log&from=' + from + 'to=' + to
          });
        }
        //#endregion
        //#region getSensotData
        function getErrorData(from, to) {
          //DATA1503.JSN
          return $http({
            method: "get",
            url: host + '/sd/api.php?action=get_error_log&from=' + from + 'to=' + to
          });
        }
        //#endregion
        
        //#region getSensotData
        function getSensorStatus() {
          return $http({
            method: "get",
            url: host + '/sd/api.php?action=get_sensor_and_uptime_data'
          });
        }
        //#endregion



      }]);




    angular.module('greenhouse').
      value('toastr', toastr).
      value('window', window).
      factory('NotifierService',
      ['toastr', '$window', '$log',
      function NotifierService(toastr, $window, $log) {
        'use strict';

        toastr.options.timeOut = 5000;
        toastr.options.positionClass = 'toast-bottom-right';


        function notify(type) {
          return function (message, title) {
            toastr[type](message, title);
          };
        }

        return {
          error: notify('error'),
          info: notify('info'),
          success: notify('success'),
          warning: notify('warning')
        };
      }]);
  </script>
  <script id="home">
    angular.module('greenhouse').
      controller('homeController', ['$scope', 'greenApiService', 'NotifierService', '$q', '$interval',
                           function ($scope, greenApiService, NotifierService, $q, $interval) {

                             $scope.tempFormat = "C"; //or "F"
                             $scope.loaded = true; //if false shows alternate screen if API fails to refresh
                             $scope.loading; //used to show system backdrop

                             $scope.temperature = { c: 0.0, f: 0.0, address: '', name: '' };
                             $scope.humidityPercentage = { percentage: '', name: ''};
                             $scope.heatIndex = { c: 0.0, f: 0.0, name: '' };

                             $scope.light = { v: 0.0, address: '', name: '' };

                             $scope.temp1 = { c: 0.0, f: 0.0, address: '', name: '' };
                             $scope.temp2 = { c: 0.0, f: 0.0, address: '', name: '' };
                             $scope.temp3 = { c: 0.0, f: 0.0, address: '', name: '' };
                             $scope.temp4 = { c: 0.0, f: 0.0, address: '', name: '' };
                             $scope.temp5 = { c: 0.0, f: 0.0, address: '', name: '' };

                             $scope.time; //stores moment format of the date on the device
                             $scope.timeLocal;
                             $scope.timeSinceText;

                             $scope.uptime;
                             $scope.util;

                             //functions:
                             $scope.load = load;
                             $scope.calcHeatIndex = calcHeatIndex;

                             $scope.load();

                             //private:
                             function runningTime() {                              
                               $scope.timeSinceText = moment().from($scope.timeLocal, true);
                             }

                             function C2F(cel) {
                               return Math.round(((cel * 1.8) + 32)* 100) / 100;
                             }

                             function F2C(far) {
                               return Math.round(((far - 32) * 0.556) * 100) / 100;
                             }

                            function findName(addr, subaddr){

                                for (var i = 0; i < $scope.Lables.length; i++) {
                                     if( $scope.Lables[i].address === addr && $scope.Lables[i].subAddress === subaddr)
                                        return $scope.Lables[i].name;
                                  }

                                  return "N/A";
                            }

                             //public:
                             /*
                             RHumidity = Relative Humidity in %
                             tempair = air temperature in F
                             */
                             // Heat index computed using air temperature F and RH%                              
                             function calcHeatIndex(tempair, RHumidity) {
                               var hi;
                                 if (RHumidity > 100) {
                                   return 'NA'; //Relative humidity cannot exceed 100%
                                 }
                                 else if (RHumidity < 0) {
                                   return 'NA'; //Relative humidity cannot be less than 0%
                                 }
                                 else if (tempair <= 40.0) {
                                   hi = tempair;
                                 }
                                 else {
                                   var hitemp = 61.0 + ((tempair - 68.0) * 1.2) + (RHumidity * 0.094);
                                   var fptemp = parseFloat(tempair);
                                   var hifinal = 0.5 * (fptemp + hitemp);

                                   if (hifinal > 79.0) {
                                     hi = -42.379 + 2.04901523 * tempair + 10.14333127 * RHumidity - 0.22475541 * tempair * RHumidity - 6.83783 * (Math.pow(10, -3)) * (Math.pow(tempair, 2)) - 5.481717 * (Math.pow(10, -2)) * (Math.pow(RHumidity, 2)) + 1.22874 * (Math.pow(10, -3)) * (Math.pow(tempair, 2)) * RHumidity + 8.5282 * (Math.pow(10, -4)) * tempair * (Math.pow(RHumidity, 2)) - 1.99 * (Math.pow(10, -6)) * (Math.pow(tempair, 2)) * (Math.pow(RHumidity, 2));
                                     if ((RHumidity <= 13) && (tempair >= 80.0) && (tempair <= 112.0)) {
                                       var adj1 = (13.0 - RHumidity) / 4.0;
                                       var adj2 = Math.sqrt((17.0 - Math.abs(tempair - 95.0)) / 17.0);
                                       var adj = adj1 * adj2;
                                       hi = hi - adj;
                                     }
                                     else if ((RHumidity > 85.0) && (tempair >= 80.0) && (tempair <= 87.0)) {
                                       var adj1 = (RHumidity - 85.0) / 10.0;
                                       var adj2 = (87.0 - tempair) / 5.0;
                                       var adj = adj1 * adj2;
                                       hi = hi + adj;
                                     }
                                   }
                                   else {
                                     hi = hifinal;
                                   }
                                 }
                                 var heatindex = Math.round(hi * 100) / 100; // + " F" + " / " + Math.round((hi - 32) * .556) + " C";
                                 var tempc2 = (tempair - 32) * .556;
                                 var rh2 = 1 - RHumidity / 100;
                                 var tdpc2 = tempc2 - (((14.55 + .114 * tempc2) * rh2) + (Math.pow(((2.5 + .007 * tempc2) * rh2), 3)) + ((15.9 + .117 * tempc2)) * (Math.pow(rh2, 14)));

                                 return heatindex;                                

                             }

                             function load() {
                               $scope.loaded = true;


                               $scope.loading = greenApiService.getSensorStatus().then(
                                   function success(data) {

                                     $scope.time = moment(data.data.result.uptime.deviceTime.dateTime, "YYYY-MM-DD HH:mm:ss");
                                     $scope.timeSinceText = moment(data.data.result.uptime.deviceTime.dateTime, "YYYY-MM-DD HH:mm:ss");
  
                                     $scope.timeLocal = moment();
                                     $scope.stopTime = $interval(runningTime, 1000);
                                     $scope.Lables =  data.data.result.sensorLabels.sensors;
                                     
                                     $scope.temperature.c = data.data.result.sensors.DHT.TempC;
                                     $scope.temperature.f = C2F(data.data.result.sensors.DHT.TempC);
                                     $scope.temperature.address = data.data.result.sensors.DHT.Address;
                                     $scope.temperature.name =  findName($scope.temperature.address, 'Temperature');

                                     $scope.humidityPercentage.percentage = data.data.result.sensors.DHT.HumidityPercent;
                                     $scope.humidityPercentage.name =  findName($scope.temperature.address, 'Humidity');                                     

                                     $scope.heatIndex.f = calcHeatIndex($scope.temperature.f, $scope.humidityPercentage.percentage);
                                     $scope.heatIndex.c = F2C($scope.heatIndex.f);
                                     $scope.heatIndex.name =  findName($scope.temperature.address, 'HeatIndex');                                     

                                     $scope.light.v = data.data.result.sensors.Light.Light;
                                     $scope.light.address = data.data.result.sensors.Light.Address;
                                     $scope.light.name =  findName($scope.light.address, null);                                        

                                     $scope.temp1.c = data.data.result.sensors.Temperatures[0].TempC;
                                     $scope.temp1.address = data.data.result.sensors.Temperatures[0].Address;
                                     $scope.temp1.f = C2F($scope.temp1.c);
                                     $scope.temp1.name =  findName($scope.temp1.address, null); 

                                     $scope.temp2.c = data.data.result.sensors.Temperatures[1].TempC;
                                     $scope.temp2.address = data.data.result.sensors.Temperatures[1].Address;
                                     $scope.temp2.f = C2F($scope.temp2.c);
                                     $scope.temp2.name =  findName($scope.temp2.address, null); 

                                     $scope.temp3.c = data.data.result.sensors.Temperatures[2].TempC;
                                     $scope.temp3.address = data.data.result.sensors.Temperatures[2].Address;
                                     $scope.temp3.f = C2F($scope.temp3.c);
                                     $scope.temp3.name =  findName($scope.temp3.address, null); 

                                     $scope.temp4.c = data.data.result.sensors.Temperatures[3].TempC;
                                     $scope.temp4.address = data.data.result.sensors.Temperatures[3].Address;
                                     $scope.temp4.f = C2F($scope.temp4.c);
                                     $scope.temp4.name =  findName($scope.temp4.address, null); 

                                     $scope.temp5.c = data.data.result.sensors.Temperatures[4].TempC;
                                     $scope.temp5.address = data.data.result.sensors.Temperatures[4].Address;
                                     $scope.temp5.f = C2F($scope.temp5.c);
                                     $scope.temp5.name =  findName($scope.temp5.address, null); 

                                     $scope.uptime = data.data.result.uptime.alive.uptime;
                                     $scope.util = data.data.result.uptime.loadAverage.min1 + ', ' + 
                                                   data.data.result.uptime.loadAverage.min5 + ', ' + 
                                                   data.data.result.uptime.loadAverage.min15;

                                   },
                                   function error(e) {
                                     NotifierService.error("Could not load data!", "Error");
                                     $scope.loaded = false;
                                   });
                             }

                           }]);

  </script>


  <script id="directive">

    angular.module('greenhouse').
      directive('uiDatepicker', function uiDatepickerDirective() {
        'use strict';

        function link(scope, el, attr, ngModel) {
          var input = el.find('input');
          $(el).datetimepicker({
            sideBySide: true,
            showTodayButton: true,
            useCurrent: true,
            showClear: true,
            format: 'MMMM Do YYYY, HH:mm:ss (dd)'
          });
          input.on('blur keyup change', function () {
            ngModel.$setViewValue(input.val());
            //console.log(input.val());
          });
        }

        return {
          restrict: 'A',
          require: 'ngModel',
          scope: { ngModel: '=' },
          link: link
        };
      }).
    directive('ngMatch', ['$parse', function ngMatchDirective($parse) {
      'use strict';

      function link(scope, elem, attrs, ctrl) {
        // if ngModel is not defined, we don't need to do anything
        if (!ctrl) return;
        if (!attrs['ngMatch']) return;

        var valueToMatch = $parse(attrs['ngMatch']);

        var validator = function (value) {
          var temp = valueToMatch(scope),
              v = value === temp;
          ctrl.$setValidity('match', v);
          return value;
        }

        ctrl.$parsers.unshift(validator);
        ctrl.$formatters.push(validator);
        attrs.$observe('ngMatch', function () {
          validator(ctrl.$viewValue);
        });

      };
      return {
        restrict: 'A',
        require: '?ngModel',
        link: link
      };
    }]);

  </script>

  <script id="startup">
    $(document).ready(function () {
      angular.bootstrap(document, ['greenhouse']);

    });
  </script>
</body>
</html>