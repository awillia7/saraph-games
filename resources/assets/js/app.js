import "core-js/fn/object/assign";
import Vue from 'vue';

import App from '../components/App.vue';
import router from './router';
import store from './store';

import axios from 'axios';

Vue.prototype.$http = axios;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrf_token
};

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store
});
