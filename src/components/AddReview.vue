<template>
  <div class="reviews-container mt-4">
    <h4>Reviews</h4>

    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Add Your Review</h5>
        <div class="row g-2 align-items-center">
          <div class="col-md-2">
            <select v-model="newReview.rating" class="form-select">
              <option v-for="n in 5" :key="n" :value="n">{{ n }} ⭐</option>
            </select>
          </div>
          <div class="col-md-7">
            <input
              v-model="newReview.comment"
              class="form-control"
              placeholder="Share your thoughts about this restaurant"
              maxlength="200"
            />
          </div>
          <div class="col-md-3">
            <button @click="addReview" class="btn btn-primary w-100">Add</button>
          </div>
        </div>
        <div v-if="message" class="alert alert-info mt-2">{{ message }}</div>
      </div>
    </div>

    <!-- Review list -->
    <div v-if="reviews.length === 0" class="text-center p-3">
      No reviews yet. Be the first to review!
    </div>

    <div v-else class="reviews-list">
      <div v-for="(review, index) in reviews" :key="review.review_id" class="card mb-2">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="fw-bold">{{ review.user }}</div>
          
            <div>{{ getStars(review.rating) }}</div>
            <div v-if="editingIndex !== index">
              <span>{{ review.comment }}</span>
            </div>
            <div v-else>
              <input v-model="editComment" class="form-control form-control-sm" maxlength="200" />
              <button @click="saveEdit(index)" class="btn btn-success btn-sm mt-1 me-1">Save</button>
              <button @click="cancelEdit" class="btn btn-secondary btn-sm mt-1">Cancel</button>
            </div>
          </div>
          <div>
            <button
              v-if="review.user === userName && editingIndex !== index"
              @click="startEdit(index, review.comment)"
              class="btn btn-warning btn-sm me-2"
            >
              Edit
            </button>
            <button
              v-if="review.user === userName && editingIndex !== index"
              @click="deleteReview(index, review.review_id)"
              class="btn btn-danger btn-sm"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['restaurant_id'],
  data() {
    return {
     
      reviews: [],
      newReview: {
        rating: 5,
        comment: '',
      },
      message: '',
      editingIndex: null,
      editComment: '',
      userName: '',
    }
  },
  mounted() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (user && user.username) {
        this.userName = user.username;
      }
      this.loadReviews();
  },
  watch: {
    restaurant_id() {
      this.loadReviews();
    }
  },
  methods: { 
    async loadReviews() {
      try {
        const response = await fetch(`/cos30043/s104101431/test/resources/apis.php/reviews?restaurant_id=${this.restaurant_id}`, {
          method: 'GET',
          headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) {
          throw new Error('Failed to fetch reviews');
        }

        const data = await response.json();
        this.reviews = data;
      } catch (error) {
        console.error('Error loading reviews:', error);
        this.message = 'Error loading reviews. Please try again later.';
      }
    },
    // add review
    async addReview() {
        //if not empty
      if (this.newReview.comment.trim() === '') {
        this.message = 'Please enter a comment';
        return;
      }

      const review = {
        restaurant_id: this.restaurant_id,
        user: this.userName,
        rating: this.newReview.rating,
        comment: this.newReview.comment.trim(),
       
      };
      console.log('Review payload:', review);
      try {
        const response = await fetch('/cos30043/s104101431/test/resources/apis.php/reviews', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(review)
        });

        const data = await response.json();
        if (data.success) {
          this.reviews.unshift(data.review); // Use the returned review from the server
          this.newReview.comment = '';
          this.message = 'Review added successfully!';
        } else {
          this.message = data.error || 'Failed to add review.';
        }
      } catch (error) {
        console.error('Error adding review:', error);
        this.message = 'Server error. Please try again.';
      }
    },
    async deleteReview(index, review_id) {
      try {
        const response = await fetch(`/cos30043/s104101431/test/resources/apis.php/reviews/${review_id}`, {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
        });

        const data = await response.json();
        if (data.success) {
          this.reviews.splice(index, 1);
          this.message = 'Review deleted successfully!';
        } else {
          this.message = data.error || 'Failed to delete review.';
        }
      } catch (error) {
        console.error('Error deleting review:', error);
        this.message = 'Server error. Please try again.';
      }
    },
    getStars(rating) {
      return '⭐'.repeat(rating);
    },
    startEdit(index, comment) {
      this.editingIndex = index;
      this.editComment = comment;
    },
    saveEdit(index) {
      if (this.editComment.trim() === '') {
        this.message = 'Comment cannot be empty.';
        return;
      }
      this.reviews[index].comment = this.editComment.trim();
      this.editingIndex = null;
      this.editComment = '';
      this.message = 'Review updated!';
    },
    cancelEdit() {
      this.editingIndex = null;
      this.editComment = '';
    }
  }
};
</script>

<style scoped>
.reviews-container {
  max-width: 600px;
  margin: auto;
}
.card-title {
  font-size: 1.1rem;
}
.card {
  border-radius: 0.5rem;
}
.btn-warning {
  background-color: #ffc107;
  border: none;
  color: #212529;
}
.btn-warning:hover {
  background-color: #e0a800;
}
.btn-danger {
  background-color: #dc3545;
  border: none;
}
.btn-danger:hover {
  background-color: #bb2d3b;
}
</style>