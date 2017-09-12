import axios        from 'axios';
import Vue          from 'vue';
import { Promise }  from 'es6-promise';
import { request }  from '@/api';

let EventHandler = new Vue();
let AuthComponent = new Vue({
  data() {
    return {
      user: null,
      events: {
        loginConfirmed: () => {
          EventHandler.$emit('auth:loginConfirmed', this.user);
        },
        userUnauthenticated: () => {
          EventHandler.$emit('auth:userUnauthenticated', this.user);
        }
      },
      on: {
        loginConfirmed(fn) {
          EventHandler.$on('auth:loginConfirmed', fn);
        },
        userUnauthenticated(fn) {
          EventHandler.$on('auth:userUnauthenticated', fn);
        }
      },
    }
  },

  computed: {
    isAuthenticated() {
      return !!this.user;
    },
  },

  methods: {
    login(credentials) {
      return request('auth')
        .login({}, credentials)
        .then((response) => {
          return new Promise((resolve, reject) => {
            if ('success' === response.data.status) {
              resolve(this.getUser());
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
      return request('auth')
        .me()
        .then((response) => {
          if ('ok' === response.data.status) {
            this.user = response.data.user;

            this.events.loginConfirmed();

            return this.user;
          }

          return false;
        }, (error) => {
          return new Promise((resolve, reject) => reject(error));
        });
    },

    getUser() {
      if (this.user) {
        return new Promise((resolve, reject) => resolve(this.user));
      }

      return this.check();
    },

    logout() {
      return request('auth').logout().then((response) => {
        if ('ok' === response.data.status) {
          this.user = null;

          this.events.userUnauthenticated();
        }

        return true;
      }, (error) => {

      });
    }
  }
});

export default function Auth() {
  Vue.prototype.$auth = AuthComponent;
};
