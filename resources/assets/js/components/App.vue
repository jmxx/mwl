<template lang="html">
  <div class="Wrapper">
    <m-header></m-header>
    <div class="Container">
      <router-view></router-view>
    </div>
    <m-footer></m-footer>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log('App Component');
  }
}
</script>
