'use strict';

import path from 'path';

let rootPath    = path.resolve(process.cwd())
  , srcPath     = path.resolve(process.cwd(), 'resources/assets')
  , appPath     = path.resolve(srcPath, 'js')
  , destPath    = path.resolve(process.cwd(), 'public');

export default {
  rootPath,
  srcPath,
  appPath,
  destPath,
  entry: {
    vendor: ['vue', 'lodash', 'axios'],
    app: [
      path.join(srcPath, 'styl/bulma.scss'),
      path.join(srcPath, 'styl/app.styl'),
      path.join(srcPath, 'js/app.js')
    ]
  }
};
