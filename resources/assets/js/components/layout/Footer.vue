<template>
  <div class="Footer">
    <div class="">
      <ul>
        <li>
          <router-link to="/login">Login</router-link>
        </li>
        <li>
          <router-link to="/about">About</router-link>
        </li>
      </ul>
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
