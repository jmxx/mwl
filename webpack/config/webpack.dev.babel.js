import paths                from './paths';
import baseConfig           from './webpack.common';
import webpack              from 'webpack';
import merge                from 'webpack-merge';
import FriendlyErrorsPlugin from 'friendly-errors-webpack-plugin';

Object.keys(baseConfig.entry).forEach(function (name) {
  // this module is required to make HRM working, it's responsible for all this webpack magic
  baseConfig.entry[name] = ['./webpack/config/hot-client'].concat(baseConfig.entry[name]);
});

let config = merge(baseConfig, {
  // output: {
  //   filename: 'app/[name].bundle.js',
  //   publicPath: '/',
  //   path: paths.destPath
  // },

  devtool: '#cheap-module-eval-source-map',

  plugins: [
    // https://github.com/glenjamin/webpack-hot-middleware#installation--usage
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoEmitOnErrorsPlugin(),

    new FriendlyErrorsPlugin()
  ]
});

// config.entry.app = [
//   // this module is required to make HRM working, it's responsible for all this webpack magic:
//   'webpack-hot-middleware/client?reload=true',
// ].concat(paths.entry.app);

// config.module.rules.push({
//   test: /\.styl$/,
//   use: [
//     'css-loader',
//     'stylus-loader'
//   ]
// });

// config.plugins.push(new webpack.HotModuleReplacementPlugin());
//
// config.plugins.push(new webpack.NoEmitOnErrorsPlugin());

export default config;
