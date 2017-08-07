<template lang="html">
  <div class="fluid-container">
    <div class="columns is-vcentered">
      <div class="column">
        <div class="Login">
          <form class="Form">
            <div class="Form-element is-text-center">
              <input type="text" name="username" placeholder="Username" v-model.trim="credentials.username" @input="$v.credentials.username.$touch()">
            </div>
            <div class="Form-element is-text-center">
              <input type="password" name="password" placeholder="Password" v-model.trim="credentials.password" @input="$v.credentials.password.$touch()">
            </div>
            <div class="Form-actions is-text-center">
              <!-- <input class="Button" type="submit" name="" value="Iniciar Sesión"> -->
              <m-button :class="'is-primary'" :onClick="submit" :loading="loading" :disabled="$v.credentials.$invalid">
                Iniciar Sesión
              </m-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { minLength, required } from 'vuelidate/lib/validators'
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
  validations: {
    credentials: {
      username: {
        required
      },
      password: {
        minLength: minLength(8),
        required
      }
    }
  },
  methods: {
    submit() {
      this.loading = true;

      auth.login(this.credentials)
        .then((user) => {
          this.loading = false;
          this.$router.push('/about');
        })
        .catch((err) => {
          console.log('err', err.response.status);
          this.loading = false;
        });

      // request('auth').login({ username: 'jmxx' });

      // setTimeout(() => {
      //   this.loading = false;
      // }, 2000);
      return false;
    },
  },
  mounted() {

  }
}
</script>
