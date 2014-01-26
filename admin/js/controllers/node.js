SGWallAdminController
    .controller('NodeCtrList', function($scope, $http, $modal, $log, $routeParams, NodeService, LikeService, FlagService) {
        // Get node list by recent
        params = {};
        if($routeParams.type != 'all') {
            params.type = $routeParams.type;
            params.country = $routeParams.type;
        }

        NodeService.list(function(data){
            $scope.nodes = data;
        });

        // Switch node status
        $scope.updateStatus = function(node, status) {
            var newNode = angular.copy(node);
            newNode.status = status;
            NodeService.update(newNode,function(){
                node.status = status;
            });
        }

        // Delete node
        $scope.delete = function(node) {
            var modalInstance = $modal.open({
                templateUrl: 'tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });
            modalInstance.result.then(function () {
                $scope.nodes.splice($scope.nodes.indexOf(node), 1);
                NodeService.delete(node);
                $log.info('Node deleted at: ' + new Date());
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        // Like node - TODO: this is for testing
        $scope.like = function(node) {
            //if(!node.user_liked) {
                LikeService.post(node.nid, function(data){
                    node.user_liked = !node.user_liked;
                });
            //}
        }

        // Unlike node - TODO: this is for testing
        $scope.unlike = function(node) {
            if(node.user_liked) {
                LikeService.delete(node.nid, function(data){
                    node.user_liked = !node.user_liked;
                });
            }
        }

        // Flag node - TODO: this is for testing
        $scope.flag = function(node) {
            FlagService.post('node', node.nid, function(data){
            });
        }

        // Clean Flag node - TODO: this is for testing
        $scope.cleanFlag = function(node) {
            FlagService.delete('node', node.nid, function(data){
            });
        }

        // Next
        $scope.next = function() {
            $http.get('json/node/recent2.json')
                .success(function(data) {
                    $scope.nodes = data.data;
                })
                .error(function() {
                    $scope.error = "加载失败";
                });
        }
    })


    .controller('NodeCtrFlagged', function($scope, $http, $modal, $log, NodeService, LikeService, FlagService) {
        // Get node list by flagged
        NodeService.getFlaggedNodes(function(data){
            $scope.nodes = data;
        });

        // Switch node status
        $scope.updateStatus = function(node, status) {
            var newNode = angular.copy(node);
            newNode.status = status;
            NodeService.update(newNode,function(){
                node.status = status;
            });
        }

        // Delete node
        $scope.delete = function(node) {
            var modalInstance = $modal.open({
                templateUrl: 'tmp/dialog/delete.html',
                controller: ConfirmModalCtrl
            });
            modalInstance.result.then(function () {
                $scope.nodes.splice($scope.nodes.indexOf(node), 1);
                NodeService.delete(node);
                $log.info('Node deleted at: ' + new Date());
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

    })

    .controller('NodeCtrPost',
    function($scope, $http) {

        // Update node
        $scope.post = function(node) {
            $http.post('http://localhost:8888/bank_wall/api/index.php?r=user/login',{company_email:user.company_email, password:user.password},{headers:'object'})
                .success(function(data) {
                })
                .error(function() {
                });
        }
    })

    .controller('NodeCtrEdit',
    function($scope, $http, NodeService, $routeParams) {

        NodeService.getById($routeParams.nid, function(data){
            $scope.node = data;
        });

        // Update node
        $scope.update = function(node) {
            NodeService.update(node);
        }

        // Delete node
        $scope.delete = function(node) {
            alert(node.nid);
        }

    })

    .controller('NodeCtrNeighbor',
    function($scope, $http, NodeService, $routeParams) {

        NodeService.getNeighbor($routeParams.nid, function(data){
            $scope.node = data;
        });



    })
