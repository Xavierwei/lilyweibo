LilyAdminController
    .controller('ScarfCtrListUnapproved', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        params = {};
        params.status = 0;
        ScarfService.list(params, function(data){
            $scope.scarfs = data;
            $scope.bigTotalItems = $scope.counts.unapproved;
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
        $scope.delete = function(scarf) {
            var modalInstance = $modal.open({
                templateUrl: '../admin_asset/tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });
            modalInstance.result.then(function () {
                scarf.status = 4;
                ScarfService.update(scarf, function(data){
                    $scope.scarfs.splice($scope.scarfs.indexOf(scarf), 1);
                });
            }, function () {
            });
        }
    })

    .controller('ScarfCtrRankList', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        params = {};
        ScarfService.ranklist(params, function(data){
            $scope.scarfs = data;
            $scope.bigTotalItems = $scope.counts.approved;
            $scope.noOfPages = 0;
            $scope.currentPage = 1;
            $scope.maxSize = 5;
        });


        $scope.pageChanged = function (page) {
            params.page = page;
            ScarfService.ranklist(params, function(data){
                $scope.scarfs = data;
            });
        };

        $scope.unapprove = function (scarf) {
            var modalInstance = $modal.open({
                templateUrl: '../admin_asset/tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });
            modalInstance.result.then(function () {
                scarf.status = 0;
                ScarfService.update(scarf, function(data){
                    $scope.scarfs.splice($scope.scarfs.indexOf(scarf), 1);
                });
            }, function () {
            });
        };
    })

    .controller('ScarfCtrListAll', function($scope, $http, $modal, $log, $routeParams, ScarfService) {
        params = {};
        ScarfService.list(params, function(data){
            $scope.scarfs = data;
            $scope.bigTotalItems = $scope.counts.all;
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
        $scope.delete = function(scarf) {
            var modalInstance = $modal.open({
                templateUrl: '../admin_asset/tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });
            modalInstance.result.then(function () {
                scarf.status = 4;
                ScarfService.update(scarf, function(data){
                    $scope.scarfs.splice($scope.scarfs.indexOf(scarf), 1);
                });
            }, function () {
            });
        }
        $scope.unapprove = function (scarf) {
            var modalInstance = $modal.open({
                templateUrl: '../admin_asset/tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });
            modalInstance.result.then(function () {
                scarf.status = 0;
                ScarfService.update(scarf, function(data){
                    $scope.scarfs.splice($scope.scarfs.indexOf(scarf), 1);
                });
            }, function () {
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
        var getList = function() {
            paramsProducing = {};
            paramsProducing.status = 2;
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
        }

        getList();

        $scope.productNow = function(){
            ScarfService.produceNow(function(){
                getList();
            });
        }
    })

