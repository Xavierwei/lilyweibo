LilyAdminController
    .controller('ScarfCtrListUnapproved', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        params = {};
        params.status = 0;
        ScarfService.list(params, function(data){
            $scope.scarfs = data;
            $scope.bigTotalItems = 20;
            $scope.noOfPages = 0;
            $scope.currentPage = 1;
            $scope.maxSize = 5;
        });
        $scope.pageChanged = function (page) {
            params.page = page;
            ScarfService.list(params, function(data){
                $scope.scarfs = data;
            });
        };
        $scope.approve = function (scarf) {
            scarf.status = 1;
            ScarfService.update(scarf, function(data){
                $scope.scarfs.splice($scope.scarfs.indexOf(scarf), 1);
            });
        };
    })

    .controller('ScarfCtrListAll', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        params = {};
        ScarfService.list(params, function(data){
            $scope.scarfs = data;
            $scope.bigTotalItems = 20;
            $scope.noOfPages = 0;
            $scope.currentPage = 1;
            $scope.maxSize = 5;
        });
        $scope.pageChanged = function (page) {
            params.page = page;
            ScarfService.list(params, function(data){
                $scope.scarfs = data;
            });
        };
    })

    .controller('ScarfCtrListProduced', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        params = {};
        params.status = 3;
        ScarfService.list(params, function(data){
            $scope.scarfs = data;
            $scope.bigTotalItems = 20;
            $scope.noOfPages = 0;
            $scope.currentPage = 1;
            $scope.maxSize = 5;
        });
        $scope.pageChanged = function (page) {
            params.page = page;
            ScarfService.list(params, function(data){
                $scope.scarfs = data;
            });
        };
    })

    .controller('ScarfCtrListProducing', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        paramsProducing = {};
        paramsProducing.status = 3;
        ScarfService.list(paramsProducing, function(data){
            $scope.producing = data[0];
        });

        paramsNext = {};
        paramsNext.status = 1;
        paramsNext.pagenum = 1;
        paramsNext.page = 1;
        ScarfService.list(paramsNext, function(data){
            $scope.next = data[0];
        });

        $scope.productNow = function(){
            console.log('product now!');
        }
    })

