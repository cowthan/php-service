module.exports = function(grunt) {

    grunt.initConfig({

        less: {
            development: {
                options: {
                    compress: true
                },
                files: {
                    'web/css/src/bootstrap.css': 'web/css/src/bootstrap.less'
                }
            }
        },

        uglify: {
            main: {
                files: {
                    'web/js/main.min.js': [
                        'bower_components/jquery/dist/jquery.min.js',
                        'bower_components/bootstrap/js/tooltip.js',
                        'bower_components/codemirror/lib/codemirror.js',
                        'bower_components/codemirror/mode/htmlmixed/htmlmixed.js',
                        'bower_components/codemirror/mode/xml/xml.js',
                        'bower_components/codemirror/mode/javascript/javascript.js',
                        'bower_components/codemirror/mode/css/css.js',
                        'bower_components/codemirror/mode/clike/clike.js',
                        'bower_components/codemirror/mode/php/php.js',
                        'bower_components/browserdetection/src/browser-detection.js',
                        'bower_components/KeyboardJS/keyboard.js',
                        'web/js/src/main.js',
                        'web/js/src/canvas.js',
                        'web/js/src/loader.js'
                    ]
                }
            }
        },

        cssmin: {
            combine: {
                files: {
                    'web/css/main.min.css': [
                        'bower_components/codemirror/lib/codemirror.css',
                        'web/css/src/bootstrap.css',
                        'web/css/src/custom.css'
                    ]
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.registerTask('default', ['less', 'uglify', 'cssmin']);
};