(function () {
    'use strict';

    var wpMoviesApp = angular.module('wpMoviesApp', []);

    wpMoviesApp.controller("MoviesCtrl", function ($scope, $http) {
        $scope.movies = [];

        $scope.sync = function () {
            return $http.get($scope.url).success(function (result) {
                $scope.movies = result.data;
            });
        };

        $scope.carousel = function (){
            jQuery('.movies-container').slick();
        };

        $scope.url = '/movies-api/';

        $scope.sync();
    });

    wpMoviesApp.directive('repeatCompleted', ['$timeout', repeatCompleted]);

    function repeatCompleted($timeout){
        return function(scope, element, attrs) {
            if (scope.$last){
                $timeout(scope.carousel, 0);
            }
        };
    };
    
})();
