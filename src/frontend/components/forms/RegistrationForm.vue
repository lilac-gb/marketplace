<template>
  <b-row class="justify-content-center">
    <b-col lg="6" md="9" sm="12">
      <ValidationObserver v-slot="{ handleSubmit }">
        <b-form class="w-100 m-auto" @submit.prevent="handleSubmit(onSubmit)">
          <h1>Регистрация</h1>
          <ValidationProvider v-slot="v" rules="required|username">
            <b-form-group label="Имя:" name="first_name">
              <b-form-input
                id="firstName"
                v-model.trim="user.first_name"
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
          <ValidationProvider v-slot="v" rules="required|username">
            <b-form-group label="Фамилия:" name="last_name">
              <b-form-input
                id="firstName"
                v-model.trim="user.last_name"
                type="text"
                placeholder="Ввелите фамилию"
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
                v-model="user.email"
                type="text"
                placeholder="Введите email"
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
    </b-col>
  </b-row>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import config from '@/config/config';

export default {
  name: 'RegistrationForm',
  components: { ValidationObserver, ValidationProvider },
  data: () => ({
    user: {
      email: '',
      first_name: '',
      last_name: '',
    },
  }),
  methods: {
    async onSubmit() {
      const response = await this.$axios.post(`${config.api_url}/user/signup`, {
        headers: { Accept: 'application/json' },
        data: this.user,
      });
      console.log(response);
    },
  },
};
</script>
