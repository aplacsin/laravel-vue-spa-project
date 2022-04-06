import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify';
import '@/assets/styles/main.scss';
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

Vue.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 10,
    newestOnTop: true,
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
});
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');
