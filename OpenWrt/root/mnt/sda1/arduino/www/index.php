﻿<?php

exec("(</proc/uptime awk '{print $1}')", $secs);
$intsecs = explode(".", $secs[0]);
#------------------------------UPTIME-------------------------------------------
exec("uptime", $system); // get the uptime stats 
# no hours example:
#" 18:14:43 up 2 days, 24 min, load average: 0.17, 0.18, 0.18"
#" 22:31:20 up 2 days, 4:41, load average: 0.12, 0.17, 0.14"
$string = $system[0]; // this might not be necessary 
$uptime = explode(" up ", $string); // break up the stats into an array 
$uptimeDetails = explode("load average: ", $uptime[1]); // grab the days from the array 
$uptimeDetails[0] = trim($uptimeDetails[0]);
$uptimeDetails[0] = trim($uptimeDetails[0], ",");
$loadAverage = explode(",", $uptimeDetails[1]);


#$arr = array('uptime' => $uptimeDetails[0], 'loadAverage' => $uptimeDetails[1]);
#$arr = array('alive'        => array('secAlive' => $intsecs[0], 'aliveFor'=> secondsToTime($intsecs[0]), 'uptime' => $uptimeDetails[0]) , 
#        'loadAverage' => array('1min' => trim($loadAverage[0]), '5min' => trim($loadAverage[1]), '15min' => trim($loadAverage[2]), 'Description' => '0 is idle, 1 is fully utilized, 1.05 means 5% of processes waited for their turn.')
#      );
#exit(json_encode($arr));


function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');  
}

?>
<!DOCTYPE html>
<html manifest="cache.app1">
<!---->
<head>
  <title>Green House</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.0.0/css/bootstrap-datetimepicker.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/angular-busy/4.1.2/angular-busy.min.css" />

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

  <script type="text/ng-template" id="header.html">
    <nav class="navbar navbar-default text-center">
      <div class="container">

        <div class="navbar-header text-center">
          <a class="navbar-brand" href="/#/"><i class="fa fa-leaf green"></i> Green House</a>
        </div>

        <ul class="nav navbar-nav navbar-right">
          <li><a ng-hide="layout.isLoggedIn" href="/#/login"><i class="fa fa-wrench"></i> admin</a></li>
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
                  <td><i class="fa fa-heartbeat green"></i>  System running for <strong><span class="green"><?php print $uptimeDetails[0]; ?></span></strong> since last reboot </td>
                </tr>
                <tr class="success">
                  <td><i class="fa fa-tachometer green"></i> System utilization <strong><span class="green"><?php print $uptimeDetails[1]; ?></span></strong> </td>
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
                <tr class="success"><td>Temperature</td><td><span class="badge"><span ng-show="tempFormat == 'C'">{{temperature.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{temperature.f}} F&deg;</span></span></td><td></td></tr>
                <tr class="success"><td>Humidity</td><td> <span class="badge"><span>{{humidityPercentage}} &#37;</span> </span></td><td><!--<span class="badge">80 &#37;</span>--></td></tr>
                <tr class="success"><td>Heat Index</td><td> <span class="badge"><span ng-show="tempFormat == 'C'">{{heatIndex.c}} C&deg;</span> <span ng-hide="tempFormat == 'C'">{{heatIndex.f}} F&deg;</span> </span></td><td><!--<span class="badge">32 </span>--></td></tr>
                <tr class="success"><td>Light</td><td> <!--<span class="badge">780 lux</span>--></td><td><!--<span class="badge">780 lux</span>--></td></tr>
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
                <tr class="success"><td>Pool Level</td><td><span class="badge">5 m</span></td></tr>
                <tr class="success"><td>Top Temperature</td><td><span class="badge">12 C&deg;</span></td></tr>
                <tr class="success"><td>Bottom Temperature</td><td><span class="badge">12 C&deg; </span></td></tr>
                <tr class="success"><td>Pump Running</td><td><i class="fa fa-cog fa-spin fa-lg green"></i></td></tr>
              </tbody>
            </table>

          </div><!-- /.col-sm-6 -->
        </div><!--/row-->

        <div class="row">

          <canvas id="myChart" width="400" height="400"></canvas>
        </div>

          <div class="row">
            <div class="col-sm-6 table-responsive">
              <table class="table">
                <caption>Bed statistics</caption>
                <thead>
                  <tr class="bg-primary"><td>Bed</td><td> Level</td><td></td></tr>
                </thead>
                <tbody>
                  <tr class="success"><td>BED A</td><td><span class="badge">1</span></td><td><span class="label-success badge">10 C&deg;</span></td></tr>
                  <tr class="success"><td>BED B</td><td><span class="badge">1</span></td><td><span class="badge">80 &#37;</span></td></tr>
                  <tr class="success"><td>BED C</td><td><span class="badge">1</span></td><td><span class="badge">32 </span></td></tr>
                  <tr class="success"><td>BED D</td><td><span class="badge">1</span></td><td><span class="badge">780 lux</span></td></tr>
                  <tr class="success"><td>BED A</td><td><span class="badge">2</span></td><td><span class="label-success badge">10 C&deg;</span></td></tr>
                  <tr class="success"><td>BED B</td><td><span class="badge">2</span></td><td><span class="badge">80 &#37;</span></td></tr>
                  <tr class="success"><td>BED C</td><td><span class="badge">2</span></td><td><span class="badge">32 </span></td></tr>
                  <tr class="success"><td>BED D</td><td><span class="badge">2</span></td><td><span class="badge">780 lux</span></td></tr>
                </tbody>
              </table>
            </div><!-- /.col-sm-6 -->
            <div class="col-sm-6  table-responsive">
              <table class="table">
                <caption>Tower Statistics</caption>
                <thead>
                  <tr class="bg-primary"><td>Tower</td><td></td></tr>
                </thead>
                <tbody>
                  <tr class="success"><td>Tower 1</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 2</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 3</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 4</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 5</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 6</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 7</td><td><span class="badge">5 m</span></td></tr>
                  <tr class="success"><td>Tower 8</td><td><span class="badge">5 m</span></td></tr>

                </tbody>
              </table>

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
    angular.module('greenhouse', ['ngRoute', 'ngAnimate', 'cgBusy', 'angularMoment', 'ngMessages', 'chart.js']).
      config(['$routeProvider', function ($routeProvider) {
        $routeProvider.
         when("/", { templateUrl: "home.html", controller: "homeController" }).
         otherwise({ redirectTo: '/' });

      }])
  </script>
  <script id="service">
    angular.module('greenhouse').
      service("greenApiService", ['$http', '$q', function ($http, $q) {

        var host = 'http://miticv.duckdns.org:82/arduino';
        //var host = '';
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
            url: host + '/reboot'
          });
        }
        //#endregion
        //#region getSensotData
        function getSensorData(month, year) {
          //DATA1503.JSN
          return $http({
            method: "get",
            url: host + '/data' + year + month + '.jsn'
          });
        }
        //#endregion
        //#region getSensotData
        function getSensorStatus() {
          return $http({
            method: "get",
            url: host + '/data'
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

                             $scope.temperature = { c: 0.0, f: 0.0 };
                             $scope.humidityPercentage;
                             $scope.heatIndex = { c: 0.0, f: 0.0 };

                             $scope.time; //stores moment format of the date on the device
                             $scope.timeLocal;
                             $scope.timeSinceText;

                             //functions:
                             $scope.load = load;
                             $scope.heatIndex = heatIndex;

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

                             //public:
                             /*
                             RHumidity = Relative Humidity in %
                             tempair = air temperature in F
                             */
                             // Heat index computed using air temperature F and RH%                              
                             function heatIndex(tempair, RHumidity) {
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

                                     $scope.time = moment(data.data.DeviceTime.DateTime, "YYYY-MM-DD HH:mm:ss");
                                     $scope.timeSinceText = moment(data.data.DeviceTime.DateTime, "YYYY-MM-DD HH:mm:ss");
  
                                     $scope.timeLocal = moment();
                                     $scope.stopTime = $interval(runningTime, 1000);
                                     
                                     $scope.temperature.c = data.data.DHT.TempC;
                                     $scope.temperature.f = C2F(data.data.DHT.TempC);
                                     $scope.humidityPercentage = data.data.DHT.HumidityPercent;
                                     $scope.heatIndex.f = heatIndex($scope.temperature.f, $scope.humidityPercentage);
                                     $scope.heatIndex.c = F2C($scope.heatIndex.f);
                                     

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

  <script id="angular-chart.js">
    (function () {
      'use strict';

      Chart.defaults.global.responsive = true;
      Chart.defaults.global.multiTooltipTemplate = '<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>';

      Chart.defaults.global.colours = [
        '#97BBCD', // blue
        '#DCDCDC', // light grey
        '#F7464A', // red
        '#46BFBD', // green
        '#FDB45C', // yellow
        '#949FB1', // grey
        '#4D5360'  // dark grey
      ];

      angular.module('chart.js', [])
        .directive('chartBase', function () { return chart(); })
        .directive('chartLine', function () { return chart('Line'); })
        .directive('chartBar', function () { return chart('Bar'); })
        .directive('chartRadar', function () { return chart('Radar'); })
        .directive('chartDoughnut', function () { return chart('Doughnut'); })
        .directive('chartPie', function () { return chart('Pie'); })
        .directive('chartPolarArea', function () { return chart('PolarArea'); });

      function chart(type) {
        return {
          restrict: 'CA',
          scope: {
            data: '=',
            labels: '=',
            options: '=',
            series: '=',
            colours: '=?',
            getColour: '=?',
            chartType: '=',
            legend: '@',
            click: '='
          },
          link: function (scope, elem/*, attrs */) {
            var chart, container = document.createElement('div');
            container.className = 'chart-container';
            elem.replaceWith(container);
            container.appendChild(elem[0]);

            if (typeof window.G_vmlCanvasManager === 'object' && window.G_vmlCanvasManager !== null) {
              if (typeof window.G_vmlCanvasManager.initElement === 'function') {
                window.G_vmlCanvasManager.initElement(elem[0]);
              }
            }

            // Order of setting "watch" matter

            scope.$watch('data', function (newVal, oldVal) {
              if (!newVal || !newVal.length || (Array.isArray(newVal[0]) && !newVal[0].length)) return;
              var chartType = type || scope.chartType;
              if (!chartType) return;

              if (chart) {
                if (canUpdateChart(newVal, oldVal)) return updateChart(chart, newVal, scope);
                chart.destroy();
              }

              chart = createChart(chartType, scope, elem);
            }, true);

            scope.$watch('series', resetChart, true);
            scope.$watch('labels', resetChart, true);
            scope.$watch('options', resetChart, true);
            scope.$watch('colours', resetChart, true);

            scope.$watch('chartType', function (newVal, oldVal) {
              if (isEmpty(newVal)) return;
              if (angular.equals(newVal, oldVal)) return;
              if (chart) chart.destroy();
              chart = createChart(newVal, scope, elem);
            });

            scope.$on('$destroy', function () {
              if (chart) chart.destroy();
            });

            function resetChart(newVal, oldVal) {
              if (isEmpty(newVal)) return;
              if (angular.equals(newVal, oldVal)) return;
              var chartType = type || scope.chartType;
              if (!chartType) return;

              // chart.update() doesn't work for series and labels
              // so we have to re-create the chart entirely
              if (chart) chart.destroy();

              chart = createChart(chartType, scope, elem);
            }
          }
        };
      }

      function canUpdateChart(newVal, oldVal) {
        if (newVal && oldVal && newVal.length && oldVal.length) {
          return Array.isArray(newVal[0]) ?
          newVal.length === oldVal.length && newVal[0].length === oldVal[0].length :
            oldVal.reduce(sum, 0) > 0 ? newVal.length === oldVal.length : false;
        }
        return false;
      }

      function sum(carry, val) {
        return carry + val;
      }

      function createChart(type, scope, elem) {
        if (!scope.data || !scope.data.length) return;
        scope.getColour = typeof scope.getColour === 'function' ? scope.getColour : getRandomColour;
        scope.colours = getColours(scope);
        var cvs = elem[0], ctx = cvs.getContext('2d');
        var data = Array.isArray(scope.data[0]) ?
          getDataSets(scope.labels, scope.data, scope.series || [], scope.colours) :
          getData(scope.labels, scope.data, scope.colours);
        var chart = new Chart(ctx)[type](data, scope.options || {});
        scope.$emit('create', chart);

        if (scope.click) {
          cvs.onclick = function (evt) {
            var click = chart.getPointsAtEvent || chart.getBarsAtEvent || chart.getSegmentsAtEvent;

            if (click) {
              var activePoints = click.call(chart, evt);
              scope.click(activePoints, evt);
              scope.$apply();
            }
          };
        }
        if (scope.legend && scope.legend !== 'false') setLegend(elem, chart);
        return chart;
      }

      function getColours(scope) {
        var colours = angular.copy(scope.colours) || angular.copy(Chart.defaults.global.colours);
        while (colours.length < scope.data.length) {
          colours.push(scope.getColour());
        }
        return colours.map(convertColour);
      }

      function convertColour(colour) {
        if (typeof colour === 'object' && colour !== null) return colour;
        if (typeof colour === 'string' && colour[0] === '#') return getColour(hexToRgb(colour.substr(1)));
        return getRandomColour();
      }

      function getRandomColour() {
        var colour = [getRandomInt(0, 255), getRandomInt(0, 255), getRandomInt(0, 255)];
        return getColour(colour);
      }

      function getColour(colour) {
        return {
          fillColor: rgba(colour, 0.2),
          strokeColor: rgba(colour, 1),
          pointColor: rgba(colour, 1),
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: rgba(colour, 0.8)
        };
      }

      function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
      }

      function rgba(colour, alpha) {
        return 'rgba(' + colour.concat(alpha).join(',') + ')';
      }

      // Credit: http://stackoverflow.com/a/11508164/1190235
      function hexToRgb(hex) {
        var bigint = parseInt(hex, 16),
          r = (bigint >> 16) & 255,
          g = (bigint >> 8) & 255,
          b = bigint & 255;

        return [r, g, b];
      }

      function getDataSets(labels, data, series, colours) {
        return {
          labels: labels,
          datasets: data.map(function (item, i) {
            var dataSet = angular.copy(colours[i]);
            dataSet.label = series[i];
            dataSet.data = item;
            return dataSet;
          })
        };
      }

      function getData(labels, data, colours) {
        return labels.map(function (label, i) {
          return {
            label: label,
            value: data[i],
            color: colours[i].strokeColor,
            highlight: colours[i].pointHighlightStroke
          };
        });
      }

      function setLegend(elem, chart) {
        var $parent = elem.parent(),
            $oldLegend = $parent.find('chart-legend'),
            legend = '<chart-legend>' + chart.generateLegend() + '</chart-legend>';
        if ($oldLegend.length) $oldLegend.replaceWith(legend);
        else $parent.append(legend);
      }

      function updateChart(chart, values, scope) {
        if (Array.isArray(scope.data[0])) {
          chart.datasets.forEach(function (dataset, i) {
            (dataset.points || dataset.bars).forEach(function (dataItem, j) {
              dataItem.value = values[i][j];
            });
          });
        } else {
          chart.segments.forEach(function (segment, i) {
            segment.value = values[i];
          });
        }
        chart.update();
        scope.$emit('update', chart);
      }

      function isEmpty(value) {
        return !value ||
          (Array.isArray(value) && !value.length) ||
          (typeof value === 'object' && !Object.keys(value).length);
      }

    })();

  </script>
  <style id="angular-chart.css.map">
    .chart-legend, .bar-legend, .line-legend, .pie-legend, .radar-legend, .polararea-legend, .doughnut-legend {
      list-style-type: none;
      margin-top: 5px;
      text-align: center;
    }

      .chart-legend li, .bar-legend li, .line-legend li, .pie-legend li, .radar-legend li, .polararea-legend li, .doughnut-legend li {
        display: inline-block;
        white-space: nowrap;
        position: relative;
        margin-bottom: 4px;
        border-radius: 5px;
        padding: 2px 8px 2px 28px;
        font-size: smaller;
        cursor: default;
      }

        .chart-legend li span, .bar-legend li span, .line-legend li span, .pie-legend li span, .radar-legend li span, .polararea-legend li span, .doughnut-legend li span {
          display: block;
          position: absolute;
          left: 0;
          top: 0;
          width: 20px;
          height: 20px;
          border-radius: 5px;
        }
    /*# sourceMappingURL=angular-chart.css.map */
  </style>
</body>
</html>