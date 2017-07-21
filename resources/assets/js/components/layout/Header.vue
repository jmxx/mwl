<template>
  <div class="Header is-fixed">
    <div class="fluid-container">
      <div class="columns is-gapless is-vcentered">
        <div class="column">
          <logo />
        </div>
        <div class="column">
          <m-menu></m-menu>
        </div>
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
