<template lang="html">
  <div class="fluid-container">
    <div class="columns is-vcentered">
      <div class="column">
        <div class="Signup">
          <form class="Form">
            <!-- <div class="Form-element is-text-center">
              <input type="text" name="username" placeholder="Username" v-model="user.username">
            </div> -->
            <div class="Form-element is-text-center" :class="{ 'has-errors': $v.user.email.$error }">
              <input type="text" name="email" placeholder="Email" v-model.trim="user.email" @input="$v.user.email.$touch()">
              <span class="Form-error-message" v-if="!$v.user.email.email">Se requiere un email válido</span>
              <span class="Form-error-message" v-if="!$v.user.email.required">Este campo es requerido</span>
            </div>
            <div class="Form-element is-text-center" :class="{ 'has-errors': $v.user.password.$error }">
              <input type="password" name="password" placeholder="Password" v-model="user.password" @input="$v.user.password.$touch()">
              <span class="Form-error-message" v-if="!$v.user.password.required">Este campo es requerido</span>
              <span class="Form-error-message" v-if="!$v.user.password.minLength">Password debe contener al menos 8 caracteres</span>
            </div>
            <div class="Form-element is-text-center" :class="{ 'has-errors': $v.user.password_confirmation.$error }">
              <input type="password" name="password" placeholder="Password Confirmation" v-model="user.password_confirmation" @input="$v.user.password_confirmation.$touch()">
              <span class="Form-error-message" v-if="!$v.user.password_confirmation.sameAsPassword">Confirmación de password no es identica</span>
            </div>
            <div class="Form-actions is-text-center">
              <!-- <input class="Button" type="submit" name="" value="Iniciar Sesión"> -->
              <m-button :onClick="submit" :loading="loading" :disabled="$v.user.$invalid">
                Sign up
              </m-button>
            </div>
          </form>
        </div>
      </div>
      <!-- <pre>form: {{ $v.user }}</pre> -->
    </div>
  </div>
</template>

<script>
import { email, minLength, required, sameAs } from 'vuelidate/lib/validators';
import users from '@/core/users';

console.log(email);

export default {
  data() {
    return {
      loading: false,
      user: {
        email: '',
        password: '',
        password_confirmation: '',
      }
    }
  },
  validations: {
    user: {
      email: {
        email,
        required
      },
      password: {
        minLength: minLength(8),
        required
      },
      password_confirmation: {
        sameAsPassword: sameAs('password')
      }
    }
  },
  methods: {
    submit() {
      this.loading = true;

      users.create(this.user)
        .then((user) => {
          this.loading = false;
          this.$router.push('/login');
        })
        .catch((err) => {
          console.log('err', err.response);
          this.loading = false;
        });

      return false;
    },
  },
  mounted() {
    console.log('signup component mounted');
  }
}
</script>
