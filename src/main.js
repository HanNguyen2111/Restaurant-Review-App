
import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.js'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import { createPinia } from 'pinia';
import 'leaflet/dist/leaflet.css';


const app = createApp(App);

app.use(createPinia());
app.use(router)
app.mount('#app')
