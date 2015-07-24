/*global require, log, clean */

var gulp = require('gulp');
var args = require('yargs').argv;
var del = require('del');
var config = require('./gulp.config')();
var $ = require('gulp-load-plugins')({lazy: true});

gulp.task('vet', function () {
    'use strict';
    log('Analyzing source with JSHint and JSCS');
    return gulp
        .src(config.alljs)
        .pipe($.if(args.verbose, $.print()))
        .pipe($.jscs())
        .pipe($.jshint())
        .pipe($.jshint.reporter('jshint-stylish', {verbose: true}))
        .pipe($.jshint.reporter('fail'));
});

gulp.task('styles', ['clean-styles'], function () {
    'use strict';
    log('Copiling Less to CSS');
    return gulp
        .src(config.less)
        .pipe($.plumber())
        .pipe($.less())
        //.on('error', errorLogger)
        .pipe($.autoprefixer({browsers: ['last 2 version', '> 5%']}))
        .pipe(gulp.dest(config.styles));
});

gulp.task('clean-styles', function (done) {
    'use strict';
    var files = config.styles + '**/*.css';
    clean(files, done);
});

gulp.task('less-watcher', function () {
    'use strict';
    gulp.watch([config.less], ['styles']);

});

gulp.task('wiredep', function () {
    'use strict';
    log('Inject bower (css+js) and our app (js) => into html');
    var options = config.getWiredepDefaultOptions();
    var wiredep = require('wiredep').stream;

    return gulp
        .src(config.index)
        .pipe(wiredep(options)) // read dependancies - insert bower files (js and css)
        .pipe($.inject(gulp.src(config.js, { read: false }), config.getInjectOptions())) //insert our JS files
        .pipe(gulp.dest(config.client));
});
/*
bower install package will kick:
.bowerrc runs postinstall script which will:
run gulp wiredep (and add it to our html)
(We excluded styles from here since they might take long since less and autoprefixes compilation might take a while)
*/
gulp.task('inject', ['wiredep', 'styles'], function () {
    'use strict';
    log('Inject our app css => into html (call wiredep and styles first) ');

    return gulp
        .src(config.index)
        .pipe($.inject(gulp.src(config.css, {read: false}))) // insert our css files
        .pipe(gulp.dest(config.client));
});

////////////////////
//function errorLogger(error) {
//    log('*** Start of Error ***');
//    log(error);
//    log('*** End of Error ***');
//    this.emit('end');
//}

function clean(path, done) {
    'use strict';
    log('Cleaning: ' + $.util.colors.blue(path));
    del(path, done);
}

function log(msg) {
    'use strict';
    if (typeof (msg) === 'object') {
        for (var item in msg) {
            if (msg.hasOwnProperty(item)) {
                $.util.log($.util.colors.blue(msg[item]));
            }
        }
    } else {
        $.util.log($.util.colors.blue(msg));
    }
}
