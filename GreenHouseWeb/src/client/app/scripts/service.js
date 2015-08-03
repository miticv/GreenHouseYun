
/////////API Service

angular.module('greenhouse').
  service('greenApiService', ['$http', '$q', function ($http, $q) {

        //var host = 'http://miticv.duckdns.org:82';
        var host = '';
        return ({
            //doReboot: doReboot,
            getSensorData: getSensorData,
            getInfoData: getInfoData,
            getErrorData: getErrorData,
            getSensorDefinitionData: getSensorDefinitionData,
            getSensorStatus: getSensorStatus,


        });

        //#region doReboot
        function doReboot() {

            return $http({
                method: 'put',
                headers: { 'PP': sessionStorage.getItem('pass') },
                url: host + '/sd/api.php?action=reset_arduino'
            });
        }
        //#endregion
        //#region getSensorData
        function getSensorData(from, to, frequency) {
            // /sd/api.php?action=get_sensor_log&from=2015-08-01 14-00-00&to=2015-08-01 14-30-00&freq=10min
            // /sd/api.php?action=get_sensor_log&from=2015-08-01 14-00-00&to=2015-08-02 14-00-00&freq=60min

            if (frequency == undefined) frequency = '60min';
            if (from == undefined) from = moment().format('YYYY-MM-DD HH:mm:ss'); //use now date time
            if (to == undefined) to = moment().day(-7).format('YYYY-MM-DD HH:mm:ss');; //use past 7 days

            return $http({
                method: 'get',
                url: host + '/sd/api.php?action=get_sensor_log&from=' + from + 'to=' + to + '&freq=' + frequency
            });
        }
        //#endregion
        //#region getInfoData
        function getInfoData(from, to) {

            return $http({
                method: 'get',
                url: host + '/sd/api.php?action=get_info_log&from=' + from + 'to=' + to
            });
        }
        //#endregion
        //#region getErrorData
        function getErrorData(from, to) {

            return $http({
                method: 'get',
                url: host + '/sd/api.php?action=get_error_log&from=' + from + 'to=' + to
            });
        }
        //#endregion
        //#region getSensorDefinitionData
        function getSensorDefinitionData() {
            return $http({
                method: 'get',
                url: host + '/sd/api.php?action=get_sensor_definitions'
            });
        }
        //#endregion
        //#region getSensorStatus
        function getSensorStatus() {
            return $http({
                method: 'get',
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
