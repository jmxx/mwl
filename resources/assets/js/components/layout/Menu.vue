<template>
  <ul class="Menu">
    <li class="Menu-item">
      <router-link to="/login">Login</router-link>
    </li>
    <li class="Menu-item">
      <router-link to="/about">About</router-link>
    </li>
  </ul>
</template>

<script>
export default {
  mounted() {
    console.log('Component mounted.')
  }
}
</script>
