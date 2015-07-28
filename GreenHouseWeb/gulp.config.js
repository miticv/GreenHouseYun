module.exports = function () {
    var client = './src/client/';
    var server = './src/server/';
    var clientApp = 'app/';
    var temp = './temp/';
    var root = './';
    var bowerComponents = 'bower_components/';

    var config = {
        /**
        * file paths
        */
        alljs: [// for js-lint
            './src/**/*.js',
            './*.js',
            '!' + bowerComponents + '/**/*.*'
        ],
        temp: temp,
        build: './build/',
        client: client,
        css: temp + 'styles.css',
        fonts: bowerComponents + 'font-awesome/fonts/**/*.*',
        htmltemplates: client + '**/*.html',
        images: client + 'images/**/*.*',
        index: client + 'index.html',
        js: [
            client + clientApp + '/scripts/routing.js',
            client + clientApp + '**/*.js'
        ],
        less: [client + 'less/styles.less'],
        server: server,
        root: root,
        styles: temp,
        /***
         * optimized files
         */
        optimized: {
            app: 'app.js',
            lib: 'lib.js'
        },

        /***
         * template cache
         */
        templateCache: {
            file: 'templates.js',
            options: {
                module: 'greenhouse',
                standAlone: false,
                root: ''
            }
        },

        /**
        *  Bower and NPM locations
        */
        bower: {
            json: require('./bower.json'),
            directory: bowerComponents,
            ignorePath: '../..'
        },
        packages: [
            './package.json',
            './bower.json'
        ],
        /***
         * Node Settings
         */
        defaultPort: 1337,
        nodeServer: './src/server/server.js'
    };

    config.getInjectOptions = function () {
        var options = {
            //ignorePath: '/src/client/'
        };

        return options;
    };

    config.getWiredepDefaultOptions = function () {
        var options = {
            bowerJson: config.bower.json,
            directory: config.bower.directory,
            ignorePath: config.bower.ignorePath
            //,
            //exclude:  [ 'min.js']
        };
        return options;
    };

    return config;
};
