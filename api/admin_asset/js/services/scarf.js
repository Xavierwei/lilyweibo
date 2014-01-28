LilyAdminServices.factory( 'ScarfService', function($http, ROOT) {
    return {
        list: function(param, success) {

            $http.get(ROOT+'/scarf/list', {
                params: param,
                cache: false
            })
            .success(function(data) {
                success(data);
            })
            .error(function() {
            });
        },

        ranklist: function(param, success) {
            $http.get(ROOT+'/scarf/ranklist', {
                params: param,
                cache: false
            })
                .success(function(data) {
                    success(data.data);
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
        },




        getStatistics: function(success) {
            $http.post(ROOT+'/scarf/getStatistics')
                .success(function(data) {
                    success(data.data);
                })
                .error(function() {

                });
        },

        produceNow: function(success) {
            $http.post(ROOT+'/scarf/produceNow')
                .success(function() {
                    success();
                })
                .error(function() {

                });
        },

        logout: function(success) {
            $http.post(ROOT+'/admin/logout')
                .success(function() {
                    success();
                })
                .error(function() {

                });
        }
    };
});
