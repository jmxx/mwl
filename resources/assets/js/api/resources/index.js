import _           from 'lodash';
import axios       from 'axios';
import { replace } from '@/utils/url';

let routes = {}
  , resources = {};

function addRoute(scope = 'default', options = {}) {
  let { alias } = options;
  if (!routes[scope]) {
    routes[scope] = {};
  }

  routes[scope][alias] = options;

  console.log(`Adding ${alias} route in scope ${scope}`);
}

function route() {
  let currentScope = 'default';

  return {
    scope(scope = currentScope) {
      currentScope = scope;

      return this;
    },
    add(method, alias, url, config = {}) {
      addRoute(currentScope, {
        method, alias, url, config
      });

      return this;
    },
    get(alias, url, config = {}) {
      return this.add('GET', alias, url, config);
    },
    post(alias, url, config = {}) {
      return this.add('POST', alias, url, config);
    },
    put(alias, url, config = {}) {
      return this.add('PUT', alias, url, config);
    },
    delete(alias, url, config = {}) {
      return this.add('DELETE', alias, url, config);
    }
  }
}

function makeRequest(key, opts) {
  // Check axios request config
  // https://github.com/mzabriskie/axios#request-config
  return (params = {}, data = {}, config = {}) => {
    let { method, url } = opts;

    config.method = method;
    config.url = replace(url, params);
    config.params = params;
    config.data = data;

    return axios(config);
  };
}

function resource(scope = 'default') {
  if (!routes[scope]) {
    throw new Error(`${scope} route scope is not defined`);
  }

  if (!resources[scope]) {
    resources[scope] = {};
    _.forOwn(routes[scope], (opts, key) => {
      // here opts = { method, alias, url, config }
      resources[scope][key] = makeRequest(key, opts);
    });
  }

  return resources[scope];
}

export { route, resource, resources };
