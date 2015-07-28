# pluralsight-gulp
You've built your JavaScript application but how do you automate testing, code analysis, running it locally or deploying it? These redundant tasks can consume valuable time and resources. Stop working so hard and take advantage of JavaScript task automation using Gulp to streamline these tasks and give you back more time in the day. Studying this repo can help clarify how Gulp works, jump-start task automation with Gulp, find and resolve issues faster, and be a more productive.

## Requirements

- Install Node
	- on OSX install [home brew](http://brew.sh/) and type `brew install node`
	- on Windows install [chocolatey](https://chocolatey.org/) 
    - Read here for some [tips on Windows](http://jpapa.me/winnode)
    - open command prompt as administrator
        - type `choco install nodejs`
        - type `choco install nodejs.install`
- On OSX you can alleviate the need to run as sudo by [following these instructions](http://jpapa.me/nomoresudo). I highly recommend this step on OSX
- Open terminal
- Type `npm install -g node-inspector bower gulp`

## Quick Start
Prior to taking the course, clone this repo and run the content locally
```bash
$ npm install
$ bower install
$ npm start    (or: $ node src/server/server.js)
```
## Gulp, bower and npm

npm installs server packages( -g installs it globaly)
```bash
  npm install -g gulp bower
```
 list to show all global packages installed:
```bash
  npm list -g --depth=0 
```
--save saves to dependencies in package.json (run time dependencies: angular, bootstrap, ...)
```bash
  npm install --save
```
--save-dev saves in devDEpendencies in package.json (developer dependencies: JSHint, Uglify, Concat...)
```bash
  npm install --save-dev
```
## bower client packages

```bash
npm install -g gulp bower
```
#js
```bash
npm install --save-dev gulp-jscs gulp-jshint yargs gulp-load-plugins gulp-if gulp-print gulp-util jshint-stylish 
```
#css
```bash
npm install --save-dev gulp-less gulp-autoprefixer del gulp-plumber
```
#html
```bash
npm install --save-dev gulp-inject wiredep
```
#Automated starting dev:
```bash
npm install --save-dev gulp-nodemon
```bash

#browser syncing
```bash
npm install --save-dev browser-sync
```


# build

```bash
npm install --save-dev gulp-task-listing gulp-imagemin
```

gulp angular template cache
```bash
npm install --save-dev gulp-minify-html gulp-angular-templatecache
```

```bash
npm install --save-dev gulp-useref
```


