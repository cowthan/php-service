var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');

var debug = require('gulp-debug');

var paths = {
    cssSrc: [
        'src/Resources/css/normalize.css',
        'src/Resources/css/boilerplate.css',
        'src/Resources/css/codemirror.css',
        'src/Resources/css/laravel.css',
        'src/Resources/css/main.css'
    ],
    cssDest: 'assets/css',
    jsSrc: [
        'src/Resources/js/vendor/jquery.js',
        'src/Resources/js/vendor/plugins.js',
        'src/Resources/js/vendor/codemirror.js',
        'src/Resources/js/main.js'
    ],
    jsDest: 'assets/js',
    modernizrSrc: 'src/Resources/js/vendor/modernizr.js',
    imgSrc: 'src/Resources/img/*',
    imgDest: 'assets/img'
};

gulp.task('css', function () {
    return gulp.src(paths.cssSrc)
        .pipe(concat('style.css'))
        .pipe(gulp.dest(paths.cssDest))
        .pipe(minifyCss())
        .pipe(rename('style.min.css'))
        .pipe(gulp.dest(paths.cssDest));
});

gulp.task('js', function () {
    return gulp.src(paths.jsSrc)
        .pipe(concat('script.js'))
        .pipe(gulp.dest(paths.jsDest))
        .pipe(uglify())
        .pipe(rename('script.min.js'))
        .pipe(gulp.dest(paths.jsDest))
});

gulp.task('copy', function () {
    gulp.src(paths.modernizrSrc)
        .pipe(gulp.dest(paths.jsDest));

    gulp.src(paths.imgSrc)
        .pipe(gulp.dest(paths.imgDest));
});

gulp.task('watch', function () {
    gulp.watch(paths.cssSrc, ['css']);
    gulp.watch(paths.jsSrc, ['js']);
});

gulp.task('default', ['css', 'js', 'copy']);