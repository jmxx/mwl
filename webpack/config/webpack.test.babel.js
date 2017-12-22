import path  from 'path';
import paths from './paths';

let config = {
  devtool: 'inline-cheap-module-source-map',
  
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
    ]
  },

  externals: [ require('webpack-node-externals')() ],

};

export default config;
