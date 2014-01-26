SGWallAdminController
    .controller('CommentCtrList', function($scope, CommentService, $modal, $log, FlagService) {
        CommentService.list(function(data) {
            $scope.comments = data;
        });

        // Update node
        $scope.update = function(comment) {
            alert(comment.cid);
        }


        // Delete comment
        $scope.delete = function(comment) {
            var modalInstance = $modal.open({
                templateUrl: 'tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });

            modalInstance.result.then(function () {
                $scope.comments.splice($scope.comments.indexOf(comment), 1);
                CommentService.delete(comment);
                $log.info('Modal confirmed at: ' + new Date());
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });

        }

        // Flag node - TODO: this is for testing
        $scope.flag = function(comment) {
            FlagService.post('comment', comment.cid, function(data){
            });
        }

        // Clean Flag node - TODO: this is for testing
        $scope.cleanFlag = function(comment) {
            FlagService.delete('comment', comment.cid, function(data){
            });
        }
    })

    .controller('CommentCtrFlagged', function($scope, CommentService, $modal, $log, FlagService) {
        CommentService.getFlaggedComments(function(data) {
            $scope.comments = data;
        });

        // Update node
        $scope.update = function(comment) {
            alert(comment.cid);
        }


        // Delete comment
        $scope.delete = function(comment) {
            var modalInstance = $modal.open({
                templateUrl: 'tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });

            modalInstance.result.then(function () {
                $scope.comments.splice($scope.comments.indexOf(comment), 1);
                CommentService.delete(comment);
                $log.info('Modal confirmed at: ' + new Date());
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });

        }
    })

    .controller('CommentCtrPost', function($scope, $routeParams, CommentService) {
        $scope.save = function(comment) {
            comment.nid = $routeParams.nid;
            CommentService.post(comment, function(){

            });
        }
    })

    .controller('CommentCtrEdit', function($scope, $routeParams, CommentService) {
        CommentService.getById($routeParams.cid, function(data) {
            $scope.comment = data;
        });


        $scope.update = function(comment) {
            comment.cid = $routeParams.cid;
            CommentService.update(comment, function(){

            });
        }
    });

