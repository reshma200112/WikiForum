import { createApp } from 'vue'
import toastr from "toastr"
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import { loadFonts } from './plugins/webfontloader'
import vue3GoogleLogin from 'vue3-google-login'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
import CKEditor from '@ckeditor/ckeditor5-vue';
import {LoadingPlugin} from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import VueApexCharts from "vue3-apexcharts";
import Vue3Autocounter from 'vue3-autocounter';
// import ApexCharts from "apexcharts";

loadFonts()
library.add(fas)
const app = createApp(App).use(router).use(vuetify).use(Vue3Autocounter).use(toastr).use(Toast).use(CKEditor).use(LoadingPlugin).use(VueApexCharts).component('fa', FontAwesomeIcon)

app.use(vue3GoogleLogin, {
  clientId: '4351911953-knvrrku6snkoebu1835g67kq5s4uuc48.apps.googleusercontent.com'
})

app.mount('#app')
