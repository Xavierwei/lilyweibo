LilyAdminServices.factory( 'ScarfService', function($http, ROOT) {
    return {
        list: function(param, success) {

            $http.get(ROOT+'/scarf/list', {
                params: param
            })
            .success(function(data) {
                success(data);
            })
            .error(function() {
            });
        },

        update: function(node, success) {
            $http.post(ROOT+'/scarf/put',node)
            .success(function(data) {
                if(data.success == true) {
                    success();
                }
            })
            .error(function() {

            });
        },

        delete: function(node) {
            $http.post(ROOT+'/scarf/put',{nid:node.nid})
            .success(function(data) {
                console.log(data);
            })
            .error(function() {

            });
        }
    };
});
