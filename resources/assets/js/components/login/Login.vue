<template lang="html">
  <div class="fluid-container">
    <div class="columns is-vcentered">
      <div class="column">
        <div class="Login">
          <form class="Form">
            <div class="Form-element is-text-center">
              <input type="text" name="username" placeholder="Username" v-model="credentials.username">
            </div>
            <div class="Form-element is-text-center">
              <input type="password" name="password" placeholder="Password" v-model="credentials.password">
            </div>
            <div class="Form-actions is-text-center">
              <!-- <input class="Button" type="submit" name="" value="Iniciar Sesión"> -->
              <m-button :onClick="submit" :loading="loading">Iniciar Sesión</m-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import auth from '@/core/auth';

export default {
  data() {
    return {
      loading: false,
      credentials: {
        username: '',
        password: '',
      }
    };
  },
  methods: {
    submit() {
      this.loading = true;

      console.log(this.credentials);
      // console.log(this.username);

      auth.login(this.credentials)
        .then((user) => {
          console.log(user);
          this.loading = false;
        })
        .catch((err) => {
          console.log('err', err.response.status);
          this.loading = false;
        });

      // request('auth').login({ username: 'jmxx' });

      // setTimeout(() => {
      //   this.loading = false;
      // }, 2000);
    },
  },
  mounted() {

  }
}
</script>
