<template>
    <div class="container py-4">
        <h2 class="mb-4">Map of Restaurants</h2>
        <p>Click on the markers to see restaurant details.</p>
        <Map :restaurants="restaurants"></Map>
        <div v-if="errorMessage" class="alert alert-danger mt-4">{{ errorMessage }}</div>
        <div v-else-if="restaurants.length === 0" class="text-center mt-4">
            <p>Loading restaurants...</p>
        </div>
    </div>
</template>
<script>
import Map from '../components/Map.vue';
export default {
    components: {
        Map
    },
    data() {
        return {
            restaurants: [],
            errorMessage: '',
        };
    },
    mounted() {
        this.fetchRestaurants();
    },
    methods: {
        async fetchRestaurants() {
            this.errorMessage = '';
            try {
                const response = await fetch('/cos30043/s104101431/restaurantReview/resources/apis.php/restaurants', {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' },
                });
                
                const data = await response.json();
                if (data) {
                    this.restaurants = data;
                    
                } else {
                    this.errorMessage = 'No restaurants found';
                }
            } 
            catch (error) {
                this.errorMessage = 'Error fetching restaurants';
                console.error('Error fetching restaurants:', error);
            }
        },
    }
    }
</script>