'use strict';

/* Filters */

angular.module('LilyAdmin.filters', [])
    .filter('interpolate', ['version', function(version) {
    return function(text) {
        return String(text).replace(/\%VERSION\%/mg, version);
    }
    }])
    .filter('status', function() {
        return function(input) {
            var output;
            switch(input) {
                case '0':
                    output = 'Unapproved';
                    break;
                case '1':
                    output = 'Approved';
                    break;
                case '2':
                    output = 'Producing';
                    break;
                case '3':
                    output = 'Produced';
                    break;
            }
            return output;
        }
    })
    .filter('thumbnail', function(ROOT_FOLDER) {
        return function(input) {
            var output = input;
            if(input.indexOf('mp4') > 0) {
                output = input.replace('.mp4','_400_400.jpg');
            }
            else {
                output = input.replace('.jpg','_400_400.jpg');
            }
            return ROOT_FOLDER + output;
        }
    });
