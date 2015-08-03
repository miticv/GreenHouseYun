/* jshint -W101 */

angular.module('greenhouse').
  controller('historyController', ['$scope', 'greenApiService', 'NotifierService', '$q', '$interval',
    function ($scope, greenApiService, NotifierService, $q, $interval) {

        $scope.loaded = true; //if false shows alternate screen if API fails to refresh

        //functions:
        $scope.load = load;

        $scope.load();

        $scope.labels = []; //X - time spans
        $scope.series = []; //Y - each sensor
        $scope.data = undefined; // [time][]


        //private:
        function createGraphData(result){

            //#region demo
            //$scope.labels = ["LogDate1", "LogDate2", "LogDate3", "LogDate4", "LogDate5", "LogDate6", "LogDate7"];
            //$scope.series = ["sensorName1", "sensorName2"];
            //$scope.data = [
            //    [65, 59, 80, 81, 56, 55, 40],
            //    [28, 48, 40, 19, 86, 27, 90]
            //];
            //#endregion

            var sensorsToShow = [
                "Temp1",
                "Temp2",
                "Temp3",
                "Temp4",
                "Temp5",
                "Temp"//,
                //"Humidity",
                //"Heat Index"//,
                //"Light"
            ];


            //local variable to collect X labels
            var labels = [];

            for (var i = 0; i < result.length; i++) {
                item = result[i];

                //get time spans (X):
                if (labels.indexOf(item.logDate) < 0) {
                    labels.push(item.logDate);
                    $scope.labels.push(moment.unix(item.logDate).format("MMMM D HH:mm"));
                }
                //get all sensors (how many graphs):
                if ($scope.series.indexOf(item.sensorName) < 0) {
                    if (sensorsToShow.indexOf(item.sensorName) > -1) {
                        $scope.series.push(item.sensorName);
                    }
                }
            }
            //above loop has X and series of graphs, now initialize data object with the proper length
            $scope.data = new Array($scope.series.length);
            for (var i = 0; i < $scope.data.length; i++) {
                $scope.data[i] = new Array($scope.labels.length);
            }
            //and populate the data
            for (var i = 0; i < result.length; i++) {
                item = result[i];
                if (sensorsToShow.indexOf(item.sensorName) > -1) {
                    var lab = labels.indexOf(item.logDate);
                    var ser = $scope.series.indexOf(item.sensorName);

                    $scope.data[ser][lab] = item.value;
                }
            }

        }


        //public:
        function load() {
        $scope.loaded = true;


        $scope.loading = greenApiService.getSensorData().then(
                function success(data) {

                    createGraphData(data.data.result);


            },
            function error(e) {
                NotifierService.error("Could not load data!", "Error");
                $scope.loaded = false;
            });
        }

    }]);
