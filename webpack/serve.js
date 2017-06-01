import path                 from 'path';
import express              from 'express';
import opn                  from 'opn';
import webpack              from 'webpack';
import webpackDevMiddleware from 'webpack-dev-middleware';
import webpackHotMiddleware from 'webpack-hot-middleware';

import webpackConfig        from './config/webpack.dev.babel';

// require('./check-versions')()

// var config = require('../config')
// if (!process.env.NODE_ENV) {
//   process.env.NODE_ENV = JSON.parse(config.dev.env.NODE_ENV)
// }

// default port where dev server listens for incoming traffic
let port = process.env.PORT || 3000
  , app = express()
  , compiler = webpack(webpackConfig);

let devMiddleware = webpackDevMiddleware(compiler, {
  publicPath: webpackConfig.output.publicPath,
  quiet: true,
  stats: { colors: true }
});

let hotMiddleware = webpackHotMiddleware(compiler, {
  log: () => {}
});

// force page reload when html-webpack-plugin template changes
compiler.plugin('compilation', (compilation) => {
  compilation.plugin('html-webpack-plugin-after-emit', (data, done) => {
    hotMiddleware.publish({ action: 'reload' })
    done();
  });
});

// proxy api requests
// Object.keys(proxyTable).forEach(function (context) {
//   var options = proxyTable[context]
//   if (typeof options === 'string') {
//     options = { target: options }
//   }
//   app.use(proxyMiddleware(options.filter || context, options))
// })

app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
  next();
});

// handle fallback for HTML5 history API
app.use(require('connect-history-api-fallback')());

// serve webpack bundle output
app.use(devMiddleware);

// enable hot-reload and state-preserving
// compilation error display
app.use(hotMiddleware);

// serve pure static assets
// var staticPath = path.posix.join(config.dev.assetsPublicPath, config.dev.assetsSubDirectory)
// app.use(staticPath, express.static('./static'))

let uri = 'http://localhost:' + port
  , _resolve
  , readyPromise = new Promise(resolve => {
      _resolve = resolve;
    });

console.log('> Starting dev server...');
devMiddleware.waitUntilValid(() => {
  console.log('> Listening at ' + uri + '\n');
  // when env is testing, don't need open it
  // if (autoOpenBrowser && process.env.NODE_ENV !== 'testing') {
    opn(uri);
  // }
  _resolve();
});

let server = app.listen(port);

module.exports = {
  ready: readyPromise,
  close() {
    server.close();
  }
};
