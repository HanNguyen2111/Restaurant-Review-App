<template>
  <div class="container mt-4">
    <h1>
      <span v-if="userStore.user.username">Welcome {{ userStore.user.username }} to FoodSpace!</span>
      <span v-else>Welcome to FoodSpace!</span>
    </h1>
    <p>This is a space to find and share reviews of your favorite restaurants. Visit us to get recommended on new restaurant. You can select preferred cuisine.</p>
    <div class="row">
      <div class="col-md-6">
        <img src="../assets/restaurant1.jpg" alt="Restaurant 1" class="img-fluid rounded" />
      </div>
      <div class="col-md-6">
        <img src="../assets/restaurant2.jpg" alt="Restaurant 2" class="img-fluid rounded" />
      </div>
    </div>
  </div>
</template>
<script setup>
import {useUserStore} from '../../stores/userStore';
const userStore = useUserStore();
</script>