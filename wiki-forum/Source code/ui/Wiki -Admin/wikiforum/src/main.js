import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import Vuelidate from 'vuelidate'
import CxltToastr from 'cxlt-vue2-toastr'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

Vue.use(VueSweetalert2);
Vue.use(CxltToastr)
Vue.use(Vuelidate)

Vue.config.productionTip = false

new Vue({
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app')
