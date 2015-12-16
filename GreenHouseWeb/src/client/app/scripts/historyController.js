/* jshint -W101 */

angular.module('greenhouse').
  controller('historyController', ['$scope', 'greenApiService', 'NotifierService', '$q', '$interval', 'sensors',
    function ($scope, greenApiService, NotifierService, $q, $interval, sensors) {

        $scope.loaded = true; //if false shows alternate screen if API fails to refresh

        //functions:
        $scope.DrawGraph = DrawGraph;
        $scope.weekData = weekData;
        $scope.monthData = monthData;
        $scope.pondTemp = pondTemp;


        $scope.sDate = new Date();
        $scope.eDate = new Date();

        $scope.startDate = moment().format('YYYY-MM-DD HH:mm:ss');
        $scope.endDate = moment().day(-7).format('YYYY-MM-DD HH:mm:ss'); //use past 7 days
        $scope.freq = "60min"; //10min
        $scope.normalize = { value: true };

        $scope.sensors = sensors.data.result.sensors;
        $scope.sensorsAvailable = [];
        for (var i = 0; i < $scope.sensors.length; i++) {
            $scope.sensorsAvailable.push($scope.sensors[i].name);
        }

        $scope.sensorsSelected = [];

        $scope.labels = []; //X - time spans
        $scope.series = []; //Y - each sensor
        $scope.data = undefined; // [time][]


        //private:
        function prepareGraphData(result){

            //#region demo
            //$scope.labels = ["LogDate1", "LogDate2", "LogDate3", "LogDate4", "LogDate5", "LogDate6", "LogDate7"];
            //$scope.series = ["sensorName1", "sensorName2"];
            //$scope.data = [
            //    [65, 59, 80, 81, 56, 55, 40],
            //    [28, 48, 40, 19, 86, 27, 90]
            //];
            //#endregion
            var sensorsToShow = $scope.sensorsSelected;

            //local variable to collect X labels
            var labels = [];
            $scope.series = [];
            $scope.labels = [];

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
        function DrawGraph() {
        $scope.loaded = true;

        $scope.startDate = moment($scope.sDate).format('YYYY-MM-DD HH:mm:ss');
        $scope.endDate = moment($scope.eDate).format('YYYY-MM-DD HH:mm:ss');

        $scope.loading = greenApiService.getSensorData($scope.startDate, $scope.endDate, $scope.freq, $scope.normalize.value).then(
                function success(data) {

                    prepareGraphData(data.data.result);


            },
            function error(e) {
                NotifierService.error("Could not load data!", "Error");
                $scope.loaded = false;
            });
        }

        function weekData() {
            $scope.sDate = moment().subtract(7, 'days');
            $scope.eDate = moment(); //use past 7 days
            $scope.freq = "10min"; //10min
            //DrawGraph();
        }

        function monthData() {
            $scope.sDate = moment().subtract(1, 'month');
            $scope.eDate = moment(); //use past month
            $scope.freq = "60min"; //10min
            //DrawGraph();
        }

        function pondTemp() {

        }


    }]);
