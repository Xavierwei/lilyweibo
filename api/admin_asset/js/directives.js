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
                    elem.find('li').removeClass('active');
                    if(url.originalPath.indexOf('scarf') != -1) {
                        elem.find('li').eq(0).addClass('active');
                        scope.showSubNav = true;
                    }
                    else {
                        elem.find('li').eq(1).addClass('active');
                        scope.showSubNav = false;
                    }
                });

            }
        }

    })
    .directive('subNav',  function ($rootScope) {
        return {
            link: function (scope, elem) {
                $rootScope.$on('$routeChangeStart', function (event,url) {
                    elem.find('li').removeClass('active');
                    if(url.originalPath.indexOf('rank') != -1) {
                        elem.find('li').eq(0).addClass('active');
                        return;
                    }
                    if(url.originalPath.indexOf('produced') != -1) {
                        elem.find('li').eq(1).addClass('active');
                        return;
                    }
                    if(url.originalPath.indexOf('unapproved') != -1) {
                        elem.find('li').eq(2).addClass('active');
                        return;
                    }
                    if(url.originalPath.indexOf('all') != -1) {
                        elem.find('li').eq(3).addClass('active');
                        return;
                    }
                });

            }
        }

    })

    .directive('searchInput',  function ($rootScope) {
        return {
            link: function (scope, elem) {
                elem.bind('keyup',function(e){
                    if(e.which == 13) {
                        scope.$apply(scope.search);
                    }
                });

            }
        }

    })
