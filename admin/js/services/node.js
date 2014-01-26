SGWallAdminServices.factory( 'NodeService', function($http, ROOT) {
    return {
        list: function(success) {
            $http.get(ROOT+'/node/list?orderby=datetime&pagenum=100&showall=true')
            .success(function(data) {
                success(data);
            })
            .error(function() {
            });
        },

        getFlaggedNodes: function(success) {
            $http.get(ROOT+'/flag/getFlaggedNodes')
                .success(function(data) {
                    success(data.data);
                })
                .error(function() {
                });
        },

        getNeighbor: function(nid, success) {
            $http.get(ROOT+'/node/getNeighbor?nid='+nid)
                .success(function(data) {
                    success(data.data);
                })
                .error(function() {
                });
        },

        getById: function(nid, success) {
            $http.get(ROOT+'/node/getById?nid='+nid)
                .success(function(data) {
                    success(data.data);
                })
                .error(function() {
                });
        },

        post: function(user) {
            $http.post(ROOT+'/user/post',user)
            .success(function(data) {
                console.log(data);
            })
            .error(function() {

            });
        },

        update: function(node, success) {
            $http.post(ROOT+'/node/put',node)
            .success(function(data) {
                if(data.success == true) {
                    success();
                }
            })
            .error(function() {

            });
        },

        delete: function(node) {
            $http.post(ROOT+'/node/delete',{nid:node.nid})
            .success(function(data) {
                console.log(data);
            })
            .error(function() {

            });
        }
    };
});
