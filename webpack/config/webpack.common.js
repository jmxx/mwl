import path               from 'path';
import HtmlWebpackPlugin  from 'html-webpack-plugin';
import ExtractTextPlugin  from 'extract-text-webpack-plugin';
import ManifestPlugin     from 'webpack-manifest-plugin';

import paths from './paths';

export default {
  entry: paths.entry,
  output: {
    filename: 'js/[name].bundle.js',
    publicPath: 'http://localhost:3000/',
    path: paths.destPath
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': path.resolve(paths.rootPath, 'node_modules/vue/dist/vue.esm.js'),
      '@': path.resolve(paths.appPath)
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: 'vue-loader',
      },
      {
        test: /\.js$/,
        use: 'babel-loader',
        include: [ path.resolve(paths.appPath) ]
      },
      {
        test: /\.styl$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            'css-loader',
            'stylus-loader'
          ]
        })
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin({
      filename: '[name].css',
      disable: true
    }),

    new ManifestPlugin(),

    // new HtmlWebpackPlugin({
    //   template: 'src/index.html',
    //   inject: true,
    //   // inject: 'body',
    //   hash: true,
    //   // chunks: ['vendors', 'app']
    // }),
  ]
};
