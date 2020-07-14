<template>
  <ValidationObserver v-slot="{ handleSubmit }">
    <b-form class="w-50 m-auto" @submit.prevent="handleSubmit(login)">
      <ValidationProvider v-slot="v" rules="required|email">
        <b-form-group label="Email" label-for="email">
          <b-form-input
            id="email"
            v-model="email"
            type="email"
            placeholder="Введите email"
            :class="{
              'is-invalid': v.invalid && (v.touched || v.changed),
              'is-valid': v.valid,
            }"
          ></b-form-input>
          <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
            {{ v.errors[0] }}
          </b-form-invalid-feedback>
        </b-form-group>
      </ValidationProvider>
      <ValidationProvider v-slot="v" rules="required">
        <b-form-group label="Password" label-for="password">
          <b-form-input
            id="password"
            v-model="password"
            type="password"
            placeholder="Введите пароль"
            :class="{
              'is-invalid': v.invalid && v.touched,
              'is-valid': v.valid,
            }"
          >
          </b-form-input>
          <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
            {{ v.errors[0] }}
          </b-form-invalid-feedback>
        </b-form-group>
      </ValidationProvider>
      <b-button type="submit" variant="primary">Отправить</b-button>
    </b-form>
  </ValidationObserver>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import config from '@/config/config';

export default {
  name: 'LoginForm',
  components: { ValidationProvider, ValidationObserver },
  data() {
    return {
      email: '',
      password: '',
      error: null,
    }
  },

  methods: {
    async login() {
      try {
        await this.$auth.loginWith('local', {
          data: {
            email: this.email,
            password: this.password
          }
        });

        // this.$router.push('/')
      } catch (e) {
        console.log(e.response);
      }
    }
  }
};
</script>

<style scoped lang="scss"></style>
