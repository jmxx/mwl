import Vue        from 'vue';
import VueRouter  from 'vue-router';
import Vuelidate  from 'vuelidate';

import Auth       from '@/core/auth';

const About = { template: '<div>About</div>' };
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

Vue.use(VueRouter);
Vue.use(Vuelidate);
Vue.use(Auth);

window.Vue = Vue;

const LoginComponent = require('./components/login/Login.vue');
const SignupComponent = require('./components/signup/Signup.vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('app', require('./components/App.vue'));

Vue.component('welcome', require('./components/Welcome.vue'));
Vue.component('m-header', require('./components/layout/Header.vue'));
Vue.component('m-menu', require('./components/layout/Menu.vue'));
Vue.component('m-button', require('./components/ui/Button.vue'));
Vue.component('m-footer', require('./components/layout/Footer.vue'));
Vue.component('logo', require('./components/Logo.vue'));

const router = new VueRouter({
  routes: [
    { path: '/login', name: 'login', component: LoginComponent, meta: { allowGuests: true } },
    { path: '/signup', name: 'signup', component: SignupComponent, meta: { allowGuests: true } },
    { path: '/logout', name: 'logout', beforeEnter: logout },
    { path: '/about', component: About }
  ]
});

function logout(to, from, next) {
  router.app.$auth.logout();

  return next({ name: 'login' });
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.allowGuests)) {
    return next();
  }

  if ('logout' === to.name) {
    return next();
  }

  router.app.$auth.getUser()
    .then((user) => {
      next();
    })
    .catch((err)=> {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      });
    });
});


const app = new Vue({
  beforeMount() {
    console.log('before mount');

    this.$auth.on.loginConfirmed((user) => {
      console.log('login', user);
    });

    this.$auth.on.userUnauthenticated((user) => {
      console.log('logout', user);
    });
  },

  mounted() {

  },

  router
}).$mount('#app');
