<template>
  <ValidationObserver v-slot="{ handleSubmit }">
    <b-form class="w-50 m-auto" @submit.prevent="handleSubmit(onSubmit)">
      <h1>Регистрация</h1>
      <ValidationProvider v-slot="v" rules="required|username">
        <b-form-group label="Имя:" name="name">
          <b-form-input
            id="firstName"
            v-model="firstName"
            type="text"
            placeholder="Ввелите имя"
            :class="{
              'is-invalid': v.invalid && (v.touched || v.changed),
              'is-valid': v.valid,
            }"
          >
          </b-form-input>
          <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
            {{ v.errors[0] }}
          </b-form-invalid-feedback>
        </b-form-group>
      </ValidationProvider>
      <ValidationProvider v-slot="v" rules="required|email">
        <b-form-group label="Email:" label-for="userName">
          <b-input
            id="email"
            v-model="email"
            type="text"
            placeholder="Введите имя"
            :class="{
              'is-invalid': v.invalid && v.touched,
              'is-valid': v.valid,
            }"
          />
          <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
            {{ v.errors[0] }}
          </b-form-invalid-feedback>
          <p class="pt-3">
            На этот email будет выслано письмо с активацией
          </p>
        </b-form-group>
      </ValidationProvider>
      <b-button type="submit" variant="primary">Отправить письмо</b-button>
    </b-form>
  </ValidationObserver>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
  name: 'RegistrationForm',
  components: { ValidationObserver, ValidationProvider },
  data: () => ({
    email: '',
    firstName: '',
  }),
  methods: {
    async onSubmit() {
      const response = await this.$axios.post(
        'https://marketplace.docker/user/signup',
        {
          email: this.email,
          first_name: this.firstName,
        }
      );
      console.log(response);
    },
  },
};
</script>

<style scoped lang="scss"></style>
