module.exports = function () {
    var client = './src/client/';
    var clientApp = 'app/';
    var styles = 'styles/';
    var bower_components = client + 'bower_components/';

    var config = {
        styles: client + styles,
        /**
        * file paths
        */
        alljs: [ // for js-lint
            './src/**/*.js',
            './*.js',
            '!./**/*.min.js'
        ],
        client: client,
        css: styles + 'styles.css',
        index: client + 'index.html',
        js: [
            client + clientApp + '/scripts/routing.js',
            client + clientApp + '**/*.js'
        ],
        less: [client + 'less/styles.less'],
        /**
        *  Bower and NPM locations
        */
        bower: {
            json: require('./bower.json'),
            directory: bower_components,
            ignorePath: '../..'
        }
    };

    config.getInjectOptions = function () {
        var options = {
            ignorePath: '/src/client/'
        };

        return options;
    }

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
