import axios        from 'axios';
import Vue          from 'vue';
import { Promise }  from 'es6-promise';
import { request }  from '@/api';

let user = null
  , EventHandler = new Vue();

let auth = {
  events: {
    loginConfirmed() {
      EventHandler.$emit('auth:loginConfirmed', user);
    },
    userUnauthenticated() {
      EventHandler.$emit('auth:userUnauthenticated', user);
    }
  },

  on: {
    loginConfirmed: (fn) => {
      EventHandler.$on('auth:loginConfirmed', fn);
    },
    userUnauthenticated: (fn) => {
      EventHandler.$on('auth:userUnauthenticated', fn);
    }
  },

  login(credentials) {
    return request('auth')
      .login({}, credentials)
      .then((response) => {
        return new Promise((resolve, reject) => {
          console.log((response));
          if ('success' === response.data.status) {
            resolve(auth.user());
          } else {
            reject(false);
          }
        });

        // return 'success' === response.data.status
        //   ? auth.user()
        //   : false; // return rejected promise with false;
      }, (error) => {
        return new Promise((resolve, reject) => reject(error));
      });
  },

  check() {
    console.log('checking user');
    return request('auth')
      .me()
      .then((response) => {
        if ('ok' === response.data.status) {
          user = response.data.user;

          auth.events.loginConfirmed();

          return user;
        }

        return false;
      }, (error) => {
        return new Promise((resolve, reject) => reject(error));
      });
  },

  user() {
    if (user) {
      return new Promise((resolve, reject) => resolve(user));
    }

    return auth.check();
  }
}

export default auth;
