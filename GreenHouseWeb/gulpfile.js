/*global require, log, clean */

var gulp = require('gulp');
var args = require('yargs').argv;
var del = require('del');
var config = require('./gulp.config')();
var $ = require('gulp-load-plugins')({lazy: true});
var port = process.env.PORT || config.defaultPort;


gulp.task('default', ['help']);

gulp.task('help', $.taskListing);

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
    clean(config.styles + '**/*.css', done);
});

gulp.task('clean-fonts', function (done) {
    'use strict';
    clean(config.build + 'fonts/**/*.*', done);
});

gulp.task('clean-images', function (done) {
    'use strict';
    clean(config.build + 'images/**/*.*', done);
});

gulp.task('clean', function (done) {
    'use strict';
    var delconfing = [].concat(config.build, config.styles);
    log('Cleaning: ' + $.util.colors.blue(delconfing));
    del(delconfing, done);
});

gulp.task('fonts', ['clean-fonts'], function () {
    'use strict';
    log('Copying fonts');
    return gulp.src(config.fonts)
        .pipe(gulp.dest(config.build + 'fonts'));
});

gulp.task('images', ['clean-images'], function () {
    'use strict';
    log('Copying and compressing images');
    return gulp.src(config.images)
        .pipe($.imagemin({optimizationLevel: 4}))
        .pipe(gulp.dest(config.build + 'images'));
});

gulp.task('clean-code', function (done) {
    'use strict';
    var files = [].concat(
        config.styles + '**/*.css',
        config.build + '**/*.html',
        config.build + 'js/**/*.js'
    );
    clean(files, done);
});

gulp.task('less-watcher', function () {
    'use strict';
    gulp.watch([config.less], ['styles']);

});

gulp.task('templatecache', ['clean-code'], function (done) {
    'use strict';
    log('Createing AngularJS  $templateCache');
    return gulp
        .src(config.htmltemplates)
        .pipe($.minifyHtml({ empty: true }))
        .pipe($.angularTemplatecache( //gulp-angular-Templatecache
            config.templateCache.file,
            config.templateCache.options
            ))
        .pipe(gulp.dest(config.temp));
});


gulp.task('wiredep', function () {
    'use strict';
    log('Inject bower (css+js) and our app (js) => into html');
    var options = config.getWiredepDefaultOptions();
    var wiredep = require('wiredep').stream;

    return gulp
        .src(config.index)
        .pipe(wiredep(options)) // read dependancies - insert bower files (js and css)
        .pipe($.inject(gulp.src(config.js, {read: false}), config.getInjectOptions())) //insert our JS files
        .pipe(gulp.dest(config.client));
});
/*
bower install package will kick:
.bowerrc runs postinstall script which will:
run gulp wiredep (and add it to our html)
(We excluded styles from here since they might take long since less and autoprefixes compilation might take a while)
*/
gulp.task('inject', ['wiredep', 'styles', 'templatecache'], function () {
    'use strict';
    log('Inject our app css => into html (call wiredep and styles first) ');

    return gulp
        .src(config.index)
        .pipe($.inject(gulp.src(config.css, {read: false}))) // insert our css files
        .pipe(gulp.dest(config.client));
});

gulp.task('optimize', ['inject', 'fonts', 'images'], function () {
    log('Optimizing the JS, CSS and HTML');

    var templateCache = config.temp + config.templateCache.file;
    var assets = $.useref.assets({ searchPath: './' });
    var cssFilter = $.filter(['**/*.css'], { restore: true });
    //var jsFilter = $.filter(['**/*.js'], { restore: true });
    var jsAppFilter = $.filter(['**/' + config.optimized.app], { restore: true });
    var jsLibFilter = $.filter(['**/' + config.optimized.lib], { restore: true });

    return gulp
        .src(config.index)
        .pipe($.plumber())
        .pipe($.inject(gulp.src(templateCache, { read: false }), {
            starttag: '<!-- inject:template.js -->'
            }))
        .pipe(assets)
        .pipe(cssFilter)//filter down to css
        .pipe($.csso())
        .pipe(cssFilter.restore)//restore
        .pipe(jsAppFilter)//filter down to js
        .pipe($.ngAnnotate()) //{add:true}
        .pipe($.uglify())
        .pipe(jsAppFilter.restore)//restore
        .pipe(jsLibFilter)//filter down to js
        .pipe($.uglify())
        .pipe(jsLibFilter.restore)//restore
        .pipe($.rev())  //add revision
        .pipe(assets.restore())
        .pipe($.useref())
        .pipe($.revReplace())//fix revision links in html
        .pipe(gulp.dest(config.build))
        .pipe($.rev.manifest())
        .pipe(gulp.dest(config.build));
});

/***
 * Bump the version:
 * --type=pre    will pump the prerelease version *.*.*-a
 * --type=patch  or no flag will bump patch version *.*.x
 * --type=minor  will bump minor version *.x.*
 * --type=major  will bump major version x.*.*
 * --version=1.2.3 (bump to specific version and ignore other flags)
 *
 */
gulp.task('bump', function () {
    var msg = 'Bumping versions';
    var type = args.type;
    var version = args.version;
    var options = {};
    if (version) {
        options.version = version;
        msg += ' to ' + type
    } else {
        options.type = type;
        msg += ' for a ' + version
    }
    log(msg);
    return gulp
        .src(config.packages)
        .pipe($.print())
        .pipe($.bump(options))
        .pipe(gulp.dest(config.root));
});

gulp.task('serve-build', ['optimize'], function () {
    'use strict';
    serve(false);
});


gulp.task('serve-dev', ['inject'], function () {
    'use strict';
    serve(true);
});


gulp.task('test', ['vet', 'templatecache'], function () {
    startTests(true /* single run */, done);

})

////////////////////
function serve(isDev) {
    var nodeOptions = {
        script: config.nodeServer,
        delayTime: 1,
        env: {
            'PORT': port,
            'NODE_ENV': isDev ? 'dev' : 'build'
        },
        watch: [config.server]
    };
    return $.nodemon(nodeOptions)
        .on('restart', function (ev) {
        log('*** nodemon re-started');
        log('files changed on restart\n' + ev);
    })
        .on('start', function () {
        log('*** nodemon started');
    })
        .on('crash', function () {
        log('*** nodemon crashed: script crashed for some reason');
    })
        .on('exit', function () {
        log('*** nodemon exited cleanly');
    });

}

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

function startTests(singleRun, done){
    var karma = require('karma');
    var excludeFiles = [];
    var serverSpecs = config.serverIntegrationSpecs;
    excludeFiles = serverSpecs;

    karma.start({
        config: __dirname + 'karma.conf.js',
        exclude: excludeFiles,
        singleRun: !!singleRun //convert to bool
    }, karmaCompleted);

    function karmaCompleted(karmaResult){
        log('Karma Completed');

        if (karmaResult == 1) {
            done();
        } else {
            done('karma: test failed with code: ' + karmaResult);
        }

    }

}
