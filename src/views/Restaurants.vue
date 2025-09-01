<template>
  <div class="container py-4">
    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <div v-else-if="restaurants.length === 0" class="text-center">
      <p>Loading restaurants...</p>
    </div>
    <div v-else>
    <div class="row mb-3">
    <label for="nameSearch" class="form-label">Search by Name:</label>
    <input
      id="nameSearch"
      v-model="nameSearch"
      type="text"
      placeholder="Name"
      class="form-control mb-4"
    />
    <!-- type filter -->
    <div class="col-md-4">
      <label for="typeSearch" class="form-label">Filter by Type:</label>
      <select id="typeSearch" v-model="typeSearch" class="form-select">
        <option value="">All Types</option>
        <option v-for="type in uniqueTypes" :key="type" :value="type">
          {{ type }}
        </option>
      </select>
    </div>
    <!-- rating filter -->
    <div class="col-md-4">
      <label for="ratingSearch" class="form-label">Filter by Rating:</label>
      <select id="ratingSearch" v-model="ratingSearch" class="form-select">
        <option value="">All Ratings</option>
        <option v-for="rating in uniqueRatings" :key="rating" :value="rating">
          {{ rating }} ⭐
        </option>
      </select>
    </div>
  </div>

    <div class="row g-4">
      <div
        class="col-sm-12 col-md-4"  v-for="restaurant in paginatedRestaurants"
        :key="restaurant.restaurant_id"
      >
        <div class="card h-100" @click="openDetails(restaurant)">
          <img :src="getImageUrl(restaurant.image)" class="card-img-top" alt="Restaurant" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">{{ restaurant.name }}</h5>
            <p class="card-text">
              Rating: {{ restaurant.star_rating }} ⭐
              <span
                  class="float-end text-danger"
                  @click.stop="toggleFavorite(restaurant)"
                >
                  <i :class="restaurant.favorite ? 'bi bi-heart-fill' : 'bi bi-heart'"></i>
                </span>
            </p>
            <p class="card-text">Type: {{ restaurant.type }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selected" class="expanded-card-overlay" @click.self="closeDetails">
      <div class="expanded-card p-4 shadow-lg">
        <button class="btn-close float-end mx-4 my-2" @click="closeDetails"></button>
        <img :src="getImageUrl(selected.image)" class="img-fluid mb-3" alt="Restaurant" style="max-height: 300px; width: 500px; object-fit: contain; align-self: center;">
        <h3>{{ selected.name }}</h3>
        <p><strong>Type:</strong> {{ selected.type }}</p>
        <p><strong>Rating:</strong> {{ selected.star_rating }} ⭐</p>
        <span class="float-end text-danger" @click.stop="toggleFavorite(selected)">
              <i :class="selected.favorite ? 'bi bi-heart-fill' : 'bi bi-heart'"></i>
        </span>
            <AddReview :restaurant_id="selected.restaurant_id"></AddReview> 
        </div>
       
    </div>

    <!-- Pagination -->
        <nav>
        <ul class="pagination justify-content-center mt-4">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button class="page-link" @click="currentPage--" :disabled="currentPage === 1">Previous</button>
            </li>
            <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
            <button class="page-link" @click="changePage(page)">{{ page }}</button>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button class="page-link" @click="currentPage++" :disabled="currentPage === totalPages">Next</button>
            </li>
        </ul>
        </nav>
    </div>
  </div>
</template>

<script>
import AddReview from '../components/AddReview.vue';

export default {
  components: {
    AddReview,
  },
  data() {
    return {
      restaurants: [],
      reviews: [],
      nameSearch: '',
      typeSearch: '',
      ratingSearch: '',
      selected: null,
      currentPage: 1,
      itemsPerPage: 6,
      errorMessages:'', //show API errors
    };
  },
    
  computed: {
    uniqueTypes() {
        const types = this.restaurants.map(r => r.type);
        return [...new Set(types)].sort();
    },
    uniqueRatings() {
      const ratings = this.restaurants.map(r => r.star_rating);
      return [...new Set(ratings)].sort((a, b) => b - a);
    },
    filteredRestaurants() {
      let filtered = this.restaurants;
      if (this.typeSearch) {
        filtered = filtered.filter(restaurant => restaurant.type === this.typeSearch);
      }

      if (this.ratingSearch) {
        filtered = filtered.filter(restaurant => restaurant.star_rating == (this.ratingSearch));
      }
      if (this.nameSearch) {
      const term = this.nameSearch.toLowerCase();
        filtered = filtered.filter(restaurant =>
          restaurant.name.toLowerCase().includes(term)
        );
      }

      return filtered;
    },
    paginatedRestaurants() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredRestaurants.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredRestaurants.length / this.itemsPerPage);
    }
  },
  methods: {
    openDetails(restaurant) {
      this.selected = restaurant;
      this.selected.reviews = this.reviews.filter(review => review.restaurant_id === restaurant.restaurant_id);
    },
    closeDetails() {
      this.selected = null;
    },
    toggleFavorite(restaurant) {
      restaurant.favorite = !restaurant.favorite;
     
    },
    changePage(page) {
      this.currentPage = page;
    },
     getImageUrl(imageFile) {
    if (!imageFile) return '';
    if (imageFile.startsWith('http')) return imageFile;
    
    return new URL(`../assets/${imageFile}`, import.meta.url).href;
  },
  async fetchRestaurants() {
        this.errorMessages = '';
         try {
            const response = await fetch('/cos30043/s104101431/restaurantReview/resources/apis.php/restaurants', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
             })
            
            const data = await response.json();
            if (data) {
                this.restaurants = data;
                this.restaurants.forEach(r => r.favorite = false);
            } else {
                this.errorMessages = 'No restaurants found';
            }
        } 
        catch (error) {
            this.errorMessages = 'Error fetching restaurants';
            console.error('Error fetching restaurants:', error);
        }
    },
    //fetch reviews to display in the expanded card
    async fetchReviews() {
      try {
        const response = await fetch('/cos30043/s104101431/restaurantReview/resources/apis.php/reviews', {
          method: 'GET',
          headers: { 'Content-Type': 'application/json' },
        });

        const data = await response.json();
        if (data) {
          this.reviews = data; 
        } else {
          console.error('No reviews found');
        }
      } catch (error) {
        console.error('Error fetching reviews:', error);
      }
    }
   },

  mounted() {
        this.fetchRestaurants();
        this.fetchReviews();
    },
};
</script>

<style scoped>
.expanded-card-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.expanded-card {
  background: white;
  width: 90%;
  max-width: 600px;
  border-radius: 1rem;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
}

.card:hover {
  cursor: pointer;
  transition: transform 0.2s ease-in-out;
  transform: translateY(-5px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-img-top {
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
  object-fit: cover;
  height: 200px;      
}

.pagination {
  margin-top: 1rem;
}

.page-item.disabled .page-link {
  pointer-events: none;
  opacity: 0.5;
}
span.text-danger {
  font-size: 1.2rem;
  cursor: pointer;
}
</style>
