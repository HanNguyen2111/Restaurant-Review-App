<template>
    <div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <router-link class="navbar-brand" to="/home">FoodieReview</router-link>
        <button 
          class="navbar-toggler" 
          type="button" 
          data-bs-toggle="collapse" 
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/home">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/restaurants">Restaurants</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/news"> News</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/about">About</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/map">View Map</router-link>
            </li>
        
          </ul>
           <div>
            <template v-if="userStore.user">
              <span class="navbar-text me-3" v-if="userStore.user.username" >Welcome, {{ userStore.user.username }}</span>
              <button @click="handleLogout" class="btn btn-outline-dark">Logout</button>
            </template>
            <template v-else>
              <router-link to="/login" class="btn btn-outline-dark">Login</router-link>
              <router-link to="/register" class="btn btn-outline-dark">Sign Up</router-link>
            </template>
          </div> 
        </div>
      </div>
    </nav>

      <router-view />
      <Footer></Footer>
  </div>
</template>

<script setup>
import Footer from './components/Footer.vue';
import { useUserStore } from '../stores/userStore'; // Import the user store
import { useRouter } from 'vue-router'; 

const userStore = useUserStore();
const router = useRouter(); 

const handleLogout = () => {
  userStore.logout();
  router.push('/login'); // redirect to login page after logout
};
</script>