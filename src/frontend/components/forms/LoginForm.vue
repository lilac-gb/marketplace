<template>
  <div class="forms">
  <div class="d-flex align-content-center justify-content-between">
        <h2 class="mb-3">Войти</h2>
        <h2>или</h2>
        <b-link :to="`/registration`">
          <h2>Регистрация</h2>
        </b-link>
      </div>
      <ValidationObserver v-slot="{ handleSubmit }">
        <b-form class="w-100" @submit.prevent="handleSubmit(login)">
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
              />
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
              <b-form-invalid-feedback :class="{ 'd-block': error }">
                {{ error }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                {{ v.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          <div class="d-flex justify-content-between align-items-center">
            <b-button type="submit" variant="secondary" class="background-purple">Войти</b-button>
            <b-link class="text-muted" :to="`/recovery`">Забыли пароль?</b-link>
          </div>
        </b-form>
      </ValidationObserver>
</div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
  name: 'LoginForm',
  components: { ValidationProvider, ValidationObserver },
  data() {
    return {
      email: '',
      password: '',
      error: null,
    };
  },
  
  methods: {
    async login() {
      await this.$auth.loginWith('local', {
        data: {
          email: this.email,
          password: this.password,
        },
      }).then(response => {
        console.log(response);
      }).catch(error => {
        this.error = error.response.data.data.password;
      });
    },
  },
};
</script>
