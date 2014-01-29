'use strict';

/* Controllers */

var LilyAdminController = angular.module('LilyAdmin.controllers', []);

LilyAdminController
    .controller('MainCtrl', function($scope, $http, $modal, $log,$location, $routeParams, ScarfService) {
        ScarfService.getStatistics(function(data){
            $scope.counts = {
                produced:data.produced,
                approved:data.approved,
                unapproved:data.unapproved,
                all:data.all
            };
        });

        $scope.refreshPage = function(){
            window.location.reload();
        }

        $scope.logout = function(){
            ScarfService.logout(function(){
                window.location.reload();
            });
        }

        $scope.logout = function(){
            ScarfService.logout(function(){
                window.location.reload();
            });
        }

        $scope.search = function(){
            if($scope.keyword == '') {
                $location.path( "/scarf/unapproved" );
            }
            else {
                $location.path( "/scarf/search/" + $scope.keyword );
            }
        }

    });