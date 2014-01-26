'use strict';

/* Directives */


angular.module('LilyAdmin.directives', []).
    directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
    }])
    .directive('myDialog', function() {
        return {
            restrict: 'E',
            transclude: true,
            scope: {
                'close': '&onClose'
            },
            templateUrl: 'tmp/dialog/dialog.html'
        };
    })
    .directive('checkUser',  function ($rootScope, $location, UserService) {
        return {
            link: function (scope, elem, attrs, ctrl) {
                $rootScope.$on('$routeChangeStart', function (event,url) {
                    UserService.getCurrentUser(function(data){
                        if(!data && url.originalPath != '/user/create' && url.originalPath != '/user/login'){
                            $location.path('/user/login');
                        }
                    });
                });
            }
        }

    })
    .directive('topTab',  function ($rootScope) {
        return {
            link: function (scope, elem) {
                $rootScope.$on('$routeChangeStart', function (event,url) {
                    elem.find('li').removeClass('on');
                    if(url.originalPath.indexOf('scarf') != -1) {
                        elem.find('li').eq(0).addClass('on');
                    }
                    else {
                        elem.find('li').eq(1).addClass('on');
                    }
                });

            }
        }

    })
