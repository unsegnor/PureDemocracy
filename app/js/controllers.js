'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
        .controller('MyCtrl1', ['$scope', function($scope) {
                alert("Iniciando cntrl1");
            }])
        .controller('MyCtrl2', ['$scope', function($scope) {
                alert("Iniciando cntrl2");
            }]);