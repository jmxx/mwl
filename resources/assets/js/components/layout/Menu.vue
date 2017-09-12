<template>
  <ul class="Menu">
    <li class="Menu-item" v-if="!isAuthenticated">
      <router-link to="/login">Login</router-link>
    </li>
    <li class="Menu-item" v-if="!isAuthenticated">
      <router-link to="/signup">Signup</router-link>
    </li>
    <li class="Menu-item" v-if="isAuthenticated">
      <router-link to="/logout">Log out</router-link>
    </li>
  </ul>
</template>

<script>
export default {
  computed: {
    isAuthenticated() {
      return this.$auth.isAuthenticated;
    }
  },

  mounted() {
    console.log('Component mounted.');
  }
}
</script>
