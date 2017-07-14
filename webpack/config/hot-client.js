/* eslint-disable */
require('eventsource-polyfill')

let hotClient = require('webpack-hot-middleware/client?noInfo=true&path=http://localhost:3000/__webpack_hmr');
// let hotClient = require('webpack-hot-middleware/client?noInfo=true&path=http://localhost:3000/__webpack_hmr&__webpack_public_path=http://localhost:3000');
// let hotClient = require('webpack-hot-middleware/client?noInfo=true&reload=true&path=http://localhost:3000/__webpack_hmr');

hotClient.subscribe(function (event) {
  if (event.action === 'reload') {
    window.location.reload();
  }
});
