/* jshint -W101 */

angular.module('greenhouse').
  controller('homeController', ['$scope', 'greenApiService', 'NotifierService', '$q', '$interval',
    function ($scope, greenApiService, NotifierService, $q, $interval) {

        $scope.tempFormat = "C"; //or "F"
        $scope.loaded = true; //if false shows alternate screen if API fails to refresh
        //$scope.loading; //used to show system backdrop

        $scope.temperature = { c: 0.0, f: 0.0, address: '', name: '' };
        $scope.humidityPercentage = { percentage: '', name: '' };
        $scope.heatIndex = { c: 0.0, f: 0.0, name: '' };

        $scope.light = { v: 0.0, address: '', name: '' };

        $scope.temp1 = { c: 0.0, f: 0.0, address: '', name: '' };
        $scope.temp2 = { c: 0.0, f: 0.0, address: '', name: '' };
        $scope.temp3 = { c: 0.0, f: 0.0, address: '', name: '' };
        $scope.temp4 = { c: 0.0, f: 0.0, address: '', name: '' };
        $scope.temp5 = { c: 0.0, f: 0.0, address: '', name: '' };

        $scope.veggieLight = { value: 0 , address: '', name: 'Veggie light' };
        $scope.houseLight = { value: 0 , address: '', name: 'Greenhouse Light' };
        //$scope.time; //stores moment format of the date on the device
        //$scope.timeLocal;
        //$scope.timeSinceText;

        //$scope.uptime;
        //$scope.util;

        //functions:
        $scope.load = load;
        $scope.calcHeatIndex = calcHeatIndex;

        $scope.load();

        //private:
        function runningTime() {
            $scope.timeSinceText = moment().from($scope.timeLocal, true);
        }

        function C2F(cel) {
            return Math.round(((cel * 1.8) + 32) * 100) / 100;
        }

        function F2C(far) {
            return Math.round(((far - 32) * 0.556) * 100) / 100;
        }

        function findName(addr, subaddr) {

            for (var i = 0; i < $scope.Lables.length; i++) {
                if ($scope.Lables[i].address === addr && $scope.Lables[i].subAddress === subaddr)
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
            var heatindex = Math.round(hi * 100) / 100;
            // + " F" + " / " + Math.round((hi - 32) * .556) + " C";
            var tempc2 = (tempair - 32) * .556;
            var rh2 = 1 - RHumidity / 100;
            var tdpc2 = tempc2 - (((14.55 + .114 * tempc2) * rh2) + (Math.pow(((2.5 + .007 * tempc2) * rh2), 3)) + ((15.9 + .117 * tempc2)) * (Math.pow(rh2, 14)));

            return heatindex;

        }

        function load() {
            $scope.loaded = true;


            $scope.loading = greenApiService.getSensorStatus().then(
                function success(data) {
                    //if (data.data.result == undefined) return;
                    $scope.time = moment(data.data.result.uptime.deviceTime.dateTime, "YYYY-MM-DD HH:mm:ss");
                    $scope.timeSinceText = moment(data.data.result.uptime.deviceTime.dateTime, "YYYY-MM-DD HH:mm:ss");

                    $scope.timeLocal = moment();
                    $scope.stopTime = $interval(runningTime, 1000);
                    $scope.Lables = data.data.result.sensorLabels.sensors;

                    $scope.temperature.c = data.data.result.sensors.DHT.TempC;
                    $scope.temperature.f = C2F(data.data.result.sensors.DHT.TempC);
                    $scope.temperature.address = data.data.result.sensors.DHT.Address;
                    $scope.temperature.name = findName($scope.temperature.address, 'Temperature');

                    $scope.humidityPercentage.percentage = data.data.result.sensors.DHT.HumidityPercent;
                    $scope.humidityPercentage.name = findName($scope.temperature.address, 'Humidity');

                    $scope.heatIndex.f = calcHeatIndex($scope.temperature.f, $scope.humidityPercentage.percentage);
                    $scope.heatIndex.c = F2C($scope.heatIndex.f);
                    $scope.heatIndex.name = findName($scope.temperature.address, 'HeatIndex');

                    $scope.light.v = data.data.result.sensors.Light.Light;
                    $scope.light.address = data.data.result.sensors.Light.Address;
                    $scope.light.name = findName($scope.light.address, null);

                    if (data.data.result.sensors.Temperatures[0] != undefined) {
                        $scope.temp1.c = data.data.result.sensors.Temperatures[0].TempC;
                        $scope.temp1.address = data.data.result.sensors.Temperatures[0].Address;
                        $scope.temp1.f = C2F($scope.temp1.c);
                    }
                    $scope.temp1.name = findName($scope.temp1.address, null);


                    if (data.data.result.sensors.Temperatures[1] != undefined) {
                        $scope.temp2.c = data.data.result.sensors.Temperatures[1].TempC;
                        $scope.temp2.address = data.data.result.sensors.Temperatures[1].Address;
                        $scope.temp2.f = C2F($scope.temp2.c);
                    }
                    $scope.temp2.name = findName($scope.temp2.address, null);


                    if (data.data.result.sensors.Temperatures[2] != undefined) {
                        $scope.temp3.c = data.data.result.sensors.Temperatures[2].TempC;
                        $scope.temp3.address = data.data.result.sensors.Temperatures[2].Address;
                        $scope.temp3.f = C2F($scope.temp3.c);
                    }
                    $scope.temp3.name = findName($scope.temp3.address, null);


                    if (data.data.result.sensors.Temperatures[3] != undefined) {
                        $scope.temp4.c = data.data.result.sensors.Temperatures[3].TempC;
                        $scope.temp4.address = data.data.result.sensors.Temperatures[3].Address;
                        $scope.temp4.f = C2F($scope.temp4.c);
                    }
                    $scope.temp4.name = findName($scope.temp4.address, null);


                    if (data.data.result.sensors.Temperatures[4] != undefined) {
                        $scope.temp5.c = data.data.result.sensors.Temperatures[4].TempC;
                        $scope.temp5.address = data.data.result.sensors.Temperatures[4].Address;
                        $scope.temp5.f = C2F($scope.temp5.c);
                    }
                    $scope.temp5.name = findName($scope.temp5.address, null);

                    $scope.veggieLight.value = data.data.result.sensors.DigitalPins.VeggieLight.value;
                    $scope.veggieLight.address = data.data.result.sensors.DigitalPins.VeggieLight.Address;

                    $scope.houseLight.value = data.data.result.sensors.DigitalPins.Light.value;
                    $scope.houseLight.address = data.data.result.sensors.DigitalPins.Light.Address;

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
