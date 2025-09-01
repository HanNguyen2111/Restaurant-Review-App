<template>
  <section class="vh-100">
    <div class="container-fluid">
      <div class="row">
        <!-- Login Form -->
        <div class="col-sm-6 text-black">
          <div class="px-5 ms-xl-4">
            <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
          </div>

          <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
            <form style="width: 23rem" @submit.prevent="handleLogin">
              <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px">Log in</h3>

              <div class="form-outline mb-4">
                <label class="form-label" for="username">Username</label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  class="form-control form-control-lg"
                  v-model="username"
                  required
                />
              
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="password">Password</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control form-control-lg"
                  v-model="password"
                  required
                />
                
              </div>

              <div v-if="error" class="alert alert-danger">{{ error }}</div>

              <div class="pt-1 mb-4">
                <button class="btn btn-info btn-lg btn-block" type="submit">Login</button>
              </div>

              <p class="small mb-5 pb-lg-2">
                <a class="text-muted" href="#">Forgot password?</a>
              </p>
              <p>
                Don't have an account?
                <router-link to="/register" class="link-info">Register here</router-link>
              </p>
            </form>
          </div>
        </div>

        <!-- Side Image -->
          <div class="col-sm-6 px-0 d-none d-sm-block">
          <img   
            src="../assets/loginPic.jpg"
            alt="Login image"
            class="w-100 vh-100"
            style="object-fit: cover; object-position: left"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { useUserStore } from '../../stores/userStore.js'
export default {
  data() {
    return {
      username: '',
      password: '',
      error: '',
      isLoggedIn: false
    }
  },
  methods: {
    async handleLogin() {
        this.error = ''
        const userStore = useUserStore()
        try {
          await userStore.login(this.username, this.password)
          this.$router.push('/restaurants')
        } catch (err) {
          this.error = 'Username or password incorrect.'
          console.error(err)
        }
      }
    },
}
</script>

<style scoped>
.bg-image-vertical {
  position: relative;
  overflow: hidden;
  background-repeat: no-repeat;
  background-position: right center;
  background-size: auto 100%;
}

@media (min-width: 1025px) {
  .h-custom-2 {
    height: 100%;
  }
}
</style>

<script setup>
import { useUserStore } from '../../stores/userStore.js'
const userStore = useUserStore()



</script>