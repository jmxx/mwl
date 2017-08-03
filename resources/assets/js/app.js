import Vue        from 'vue';
import VueRouter  from 'vue-router';
import auth from '@/core/auth';

const About = { template: '<div>About</div>' };
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



require('./bootstrap');

Vue.use(VueRouter);

window.Vue = Vue;


const LoginComponent = require('./components/login/Login.vue');
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
    { path: '/about', component: About }
  ]
});

auth.on.loginConfirmed((user) => {
  console.log('login', user);
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.allowGuests)) {
    return next();
  }

  auth.user()
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
  },

  router
}).$mount('#app');
