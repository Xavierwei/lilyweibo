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

        update: function(data, success) {
            $http.post(ROOT+'/scarf/UpdateStatus',data)
            .success(function(data) {
                success();
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
