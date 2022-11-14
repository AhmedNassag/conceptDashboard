require('./bootstrap');

import { createApp } from 'vue';
import store from './store/admin';
import router  from './router/adminRoute';
import Admin from "./Admin.vue";
import mitt from 'mitt';
import i18n  from './lang/admin';
import Notifications from '@kyvg/vue3-notification';
import Select2 from 'vue3-select2-component';

const emitter = mitt();

const admin = createApp(Admin);

admin.provide('emitter', emitter);

import loader from './components/loader';
import LaravelVuePagination from 'laravel-vue-pagination';

admin.component('loader', loader);
admin.component('Pagination', LaravelVuePagination);
admin.component('Select2',Select2);

admin.use(i18n).use(store).use(router).use(Notifications).mount('#admin');





