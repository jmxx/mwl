import paths       from './paths';
import baseConfig  from './webpack.common';
import webpack     from 'webpack';
import merge       from 'webpack-merge';

let config = merge(baseConfig, {
  devtool: '#source-map',

  plugins: [
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: false
      },
      sourceMap: true
    }),
  ]
});

export default config;
