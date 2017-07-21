<template>
  <div class="Footer">
    <div class="columns is-gapless">
      <div class="column is-9"></div>
      <div class="column is-3">
        <ul class="Footer-menu columns is-gapless">
          <li class="column">
            <router-link to="/login" class="Footer-link">Login</router-link>
          </li>
          <li class="column">
            <router-link to="/about" class="Footer-link">About</router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log('Component mounted.')
  }
}
</script>
