<template lang="html">
  <div class="Button" :class="classes">
    <button type="submit" name="button"  @click="onClick" :disabled="loading">
      <slot>Aceptar</slot>
    </button>
    <div class="Button-loader" v-show="loading">
      <div class="dots-loader">
        <div>loading...</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    classes() {
      return {
        'is-loading': this.loading,
        'is-disabled': this.disabled,
      };
    }
  },
  data() {
    return { show: false };
  },
  props: {
    loading: {
      default: false,
      required: false,
    },
    disabled: {
      default: false,
      required: false,
    },
    onClick: {
      required: true,
      type: Function,
    }
  },
  mounted() {
    console.log('Button compoment mounted');
  },
  methods: {

  }
}
</script>
