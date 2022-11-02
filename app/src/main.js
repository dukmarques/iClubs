import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import Cookies from 'js-cookie'

axios.defaults.headers.common['Content-Type'] = "application/json";
const authToken = Cookies.get('_adminToken');
if (authToken != null) {
    axios.defaults.headers.common['Authorization'] = "Bearer " + authToken;
}
axios.defaults.baseURL = process.env.VUE_APP_ROOT_API;

createApp(App).use(router).use(VueAxios, axios).mount('#app')
