angular.module('greenhouse', ['ngRoute', 'ngAnimate', 'ui.bootstrap', 'cgBusy', 'angularMoment', 'ngMessages', 'chart.js']).
  config(['$routeProvider', function ($routeProvider) {
    $routeProvider.
     when("/", { templateUrl: "/app/views/home.html", controller: "homeController" }).
     otherwise({ redirectTo: '/' });

  }])