module.exports = function () {
    var client = './src/client/';
    var server = './src/server/';
    var clientApp = 'app/';
    var styles = 'styles/';
    var bowerComponents = client + 'bower_components/';

    var config = {
        /**
        * file paths
        */
        alljs: [// for js-lint
            './src/**/*.js',
            './*.js',
            '!' + bowerComponents + '/**/*.*'
        ],
        build: './build/',
        client: client,
        css: styles + 'styles.css',
        fonts: bowerComponents + 'font-awesome/fonts/**/*.*',
        images: client + 'images/**/*.*',
        index: client + 'index.html',
        js: [
            client + clientApp + '/scripts/routing.js',
            client + clientApp + '**/*.js'
        ],
        less: [client + 'less/styles.less'],
        server: server,
        styles: client + styles,
        /**
        *  Bower and NPM locations
        */
        bower: {
            json: require('./bower.json'),
            directory: bowerComponents,
            ignorePath: '../..'
        },

        /***
         * Node Settings
         */
        defaultPort: 1337,
        nodeServer: './src/server/server.js'
    };

    config.getInjectOptions = function () {
        var options = {
            ignorePath: '/src/client/'
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
