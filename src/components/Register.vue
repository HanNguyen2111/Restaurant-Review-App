<template>
  <section class="text-center">
    <!-- Registration Card -->
    <div class="card mx-4 mx-md-5 bg-body-tertiary" style="margin-top: 20px; backdrop-filter: blur(30px)">
      <div class="card-body py-5 px-md-5">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-8">
            <h2 class="fw-bold mb-5">Sign up now</h2>

            <form @submit.prevent="register">
              <!-- Name Fields -->
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="form-outline">
                    <label class="form-label" for="firstName">First name</label>
                    <input
                      type="text"
                      id="firstName"
                      name="firstName"
                      class="form-control"
                      v-model="firstName"
                      required
                    />
                     <div class="text-danger" v-if="errors.firstName">{{ errors.firstName }}</div>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <div class="form-outline">
                    <label class="form-label" for="lastName">Last name</label>
                    <input
                      type="text"
                      id="lastName"
                      name="lastName"
                      class="form-control"
                      v-model="lastName"
                      required
                    />
                     <div class="text-danger" v-if="errors.lastName">{{ errors.lastName }}</div>
                  </div>
                </div>
              </div>

              <!-- Username -->
              <div class="form-outline mb-4">
                <label class="form-label" for="username">Username</label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  class="form-control"
                  v-model="username"
                  required
                />
                 <div class="text-danger" v-if="errors.username">{{ errors.username }}</div>
              </div>

              <!-- Email -->
              <div class="form-outline mb-4">
                <label class="form-label" for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control"
                  v-model="email"
                  required
                />
                 <div class="text-danger" v-if="errors.email">{{ errors.email }}</div>
              </div>

              <!-- Password -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="form-outline">
                    <label class="form-label" for="password">Password</label>
                    <input
                      type="password"
                      id="password"
                      name="password"
                      class="form-control"
                      v-model="password"
                      required
                    />
                    <div class="text-danger" v-if="errors.password">{{ errors.password }}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-outline">
                    <label class="form-label" for="confirmPassword">Confirm Password</label>
                    <input
                      type="password"
                      id="confirmPassword"
                      name="confirmPassword"
                      class="form-control"
                      v-model="confirmPassword"
                      required
                    />
                     <div class="text-danger" v-if="errors.confirmPassword">{{ errors.confirmPassword }}</div>
                  </div>
                </div>
              </div>

              <!-- Messages -->
              <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
              <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

              <!-- Submit -->
             
              <button class="btn btn-info btn-lg btn-block" type="submit">Sign Up</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      firstName: '',
      lastName: '',
      username: '',
      email: '',
      password: '',
      confirmPassword: '',
      successMessage: '',
      errorMessage: '',
      errors: {
        firstName: '',
        lastName: '',
        username: '',
        email: '',
        password: '',
        confirmPassword: ''
      }
    }
  },
  methods: {
    // Check if passwords match and meet minimum requirements
   validate: function (e) {
    this.errors.firstName = ''
    this.errors.lastName = ''
    this.errors.username = ''
    this.errors.email = ''
    this.errors.password = ''
    this.errors.confirmPassword = ''
    let valid = true
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    const namePattern = /^[A-Za-z]+$/
    const specialCharPattern = /[\$\%\^\&\*]/

    if (!namePattern.test(this.firstName)) {
      this.errors.firstName = 'First name required (letters only)'
      valid = false
    }
    if (!namePattern.test(this.lastName)) {
      this.errors.lastName = 'Last name required (letters only)'
      valid = false
    }
    if (this.username.length < 3) {
      this.errors.username = 'Username must be at least 3 characters'
      valid = false
    }
    if (!emailPattern.test(this.email)) {
      this.errors.email = 'Invalid email format'
      valid = false
    }
    if (this.password.length < 8 || !specialCharPattern.test(this.password)) {
      this.errors.password = 'Password must be at least 8 characters and contain a special character'
      valid = false
    }
    if (this.password !== this.confirmPassword) {
      this.errors.confirmPassword = 'Passwords do not match'
      valid = false
    }
    return valid
  },

    // Handle registration form submission
    async register() {
     
      this.successMessage = ''
      this.errorMessage = ''

      // Validate password
      if (!this.validate()) {
        return
      }

      try {
        
        const response = await fetch('/cos30043/s104101431/restaurantReview/resources/apiUser.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'register', 
            username: this.username,
            email: this.email,
            password: this.password,
            firstName: this.firstName,
            lastName: this.lastName,
          })
        })

        const result = await response.json()

        if (result.success) {
     
          this.successMessage = 'Registration successful! Redirecting to login...'
          setTimeout(() => {
            this.$router.push('/')
          }, 1500)
        } else {
          this.errorMessage = result.error || 'Registration failed.'
        }
      } catch (error) {
        this.errorMessage = 'Server error. Please try again later.'
        console.error('Registration error:', error)
      }
    }
  }
}
</script>
<style scoped>
.form-outline {
  position: relative;
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  text-align: left;
  color: #2c3e50;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  transition: border-color 0.15s ease-in-out;
}

.form-control:focus {
  border-color: #80bdff;
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
