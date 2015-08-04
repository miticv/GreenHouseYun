/* jshint -W101 */

angular.module('greenhouse', ['ngRoute', 'ngAnimate', 'ui.bootstrap', 'cgBusy', 'angularMoment', 'ngMessages', 'chart.js', 'ui.bootstrap.datetimepicker', 'checklist-model']).
  config(['$routeProvider', function ($routeProvider) {
        $routeProvider.
         when('/', {
            templateUrl: 'app/views/home.html',
            controller: 'homeController'
        }).
         when('/history',{
            templateUrl: 'app/views/history.html',
            controller: 'historyController',
            resolve:{
                sensors: ['greenApiService', function (greenApiService) {
                return greenApiService.getSensorDefinitionData();
                }]
            }
        }).
         otherwise({ redirectTo: '/' });
    }]);