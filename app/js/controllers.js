'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
        .controller('cLogin', ['$scope', function($scope) {
                alert("Iniciando cLogin");
            }])
        .controller('MyCtrl2', ['$scope', function($scope) {
                alert("Iniciando cntrl2");
            }]);