'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
var LilyAdminServices = angular.module('LilyAdmin.services', [])
    .value('ROOT', '/lilyweibo/api/')
    .value('ROOT_FOLDER', '');
