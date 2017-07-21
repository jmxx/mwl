import Vue        from 'vue';
import VueRouter  from 'vue-router';

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
    { path: '/login', component: LoginComponent },
    { path: '/about', component: About }
  ]
});


const app = new Vue({
  router
}).$mount('#app');
