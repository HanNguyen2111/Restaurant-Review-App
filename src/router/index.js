import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import News from '../views/News.vue'
import About from '../views/About.vue'
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import Restaurants from '../views/Restaurants.vue'
import MapView from '../views/MapView.vue'

const routes = [
  
  { path: '/', 
    redirect: '/login' },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  { path: '/news', component: News },
  { path: '/about', component: About },
  { path: '/home', name: 'Home', component: Home },
  { path: '/register', name: 'Register', component: Register },
  { path: '/restaurants', name: 'Restaurants', component: Restaurants },
  { path: '/map', component: MapView},
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router