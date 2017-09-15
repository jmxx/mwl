<template lang="html">
  <div class="Wrapper" :class="classes">
    <m-header></m-header>
    <div class="Container">
      <router-view></router-view>
    </div>
    <m-footer></m-footer>
  </div>
</template>

<script>
export default {
  computed: {
    classes() {
      return {
        'is-auth' : this.$auth.isAuthenticated
      };
    }
  },

  mounted() {
    console.log('App Component');
  }
}
</script>
