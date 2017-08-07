import axios        from 'axios';
import Vue          from 'vue';
import { Promise }  from 'es6-promise';
import { request }  from '@/api';

let user = null
  , EventHandler = new Vue();

let users = {
  events: {
    userCreated() {
      EventHandler.$emit('users:userCreated', user);
    }
  },

  on: {
    userCreated: (fn) => {
      EventHandler.$on('users:userCreated', fn);
    }
  },

  create(data) {
    return request('users')
      .create({}, data)
      .then((response) => {
        return new Promise((resolve, reject) => {
          console.log((response));
          if ('success' === response.data.status) {
            resolve(response.data.user);
          } else {
            reject(false);
          }
        });
      }, (error) => {
        return new Promise((resolve, reject) => reject(error));
      });
  }
}

export default users;
