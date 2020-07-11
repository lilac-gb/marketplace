<template>
  <b-form class="w-50 m-auto" @submit.prevent="onSubmit">
    <b-form-group label="Email" label-for="email">
      <b-form-input
        id="email"
        v-model="email"
        type="email"
        placeholder="Введите email"
      ></b-form-input>
    </b-form-group>
    <b-form-group label="Password" label-for="password">
      <b-form-input
        id="password"
        v-model="password"
        type="password"
        placeholder="Введите пароль"
      >
      </b-form-input>
    </b-form-group>
    <b-button type="submit" variant="primary">Отправить</b-button>
  </b-form>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
  name: 'LoginForm',
  components: { ValidationProvider, ValidationObserver },
  data: () => ({
    email: '',
    password: '',
  }),
  methods: {
    async onSubmit() {
      console.log('email: ', this.email);
      console.log('password', this.password);
      const response = await this.$axios.$get('https://api/marketplace/user', {
        headers: { Accept: 'application/json' },
      });

      console.log(response);
    },
  },
};
</script>

<style scoped lang="scss"></style>
