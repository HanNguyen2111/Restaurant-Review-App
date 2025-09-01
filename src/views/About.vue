<template>
  <div class="container mt-4">
    <h2>About FoodSpace</h2>
    <p>This app helps you find and review restaurants easily! Let us know your name and select the vibe</p>

    <div class="form-group my-3">
      <label>First Name</label>
      <input v-model="firstName" class="form-control" />
      <label class="mt-2">Last Name</label>
      <input v-model="lastName" class="form-control" />
      <p class="mt-3">Welcome {{ fullName }}!</p>
    </div>

    <div>
      <label>Select your vibe:</label><br />
      <input type="radio" id="mountain" value="mountain" v-model="imageChoice" />
      <label for="mountain">Mountain</label>
      <input type="radio" id="ocean" value="ocean" v-model="imageChoice" class="ms-3" />
      <label for="ocean">Ocean</label>

      <div class="mt-3">
        <img v-if="imageChoice === 'mountain'" src="../assets/mountain.jpg" class="img-fluid image rounded" />
        <img v-else-if="imageChoice === 'ocean'" src="../assets/ocean.jpg" class="img-fluid image rounded" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      firstName: '',
      lastName: '',
        imageChoice: '',
    }
  },
  computed: {
    fullName() {
      return `${this.firstName} ${this.lastName}`
    }
  }
}
</script>
<style scoped>
.image {
  max-width: 70%;
  height: auto;
}
</style>