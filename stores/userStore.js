import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    // Initialize user from localStorage 
    user: JSON.parse(localStorage.getItem('user')) || null,
    error: null
  }),
  getters: {
    isLoggedIn(state) {
       return state.user !== null && state.user !== undefined;
    }
  },
  actions: {
    async login(username, password) {
      this.error = null
      try {
        const res = await fetch('/cos30043/s104101431/restaurantReview/resources/apiUser.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password })
        })
       const data = await res.json()
        if (data) {
            this.user = data
            localStorage.setItem('user', JSON.stringify(data))
        } else {
            this.error = 'Login failed'
        }
      } catch (e) {
        this.error = 'Server error'
      }
    },
    logout() {
      this.user = null
      localStorage.removeItem('user')
    }
  }
})
