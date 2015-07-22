
/////////API Service

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

/////////Notifier Service
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
