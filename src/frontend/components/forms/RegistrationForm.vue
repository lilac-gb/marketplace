<template>
  <b-row class="justify-content-center">
    <b-col lg="6" md="9" sm="12">
      <div class="d-flex align-content-center justify-content-between">
        <h2 class="mb-3">Регистрация</h2>
        <h2>или</h2>
        <b-link :to="`/login`">
          <h2>Войти</h2>
        </b-link>
      </div>
      <ValidationObserver v-slot="{ handleSubmit }">
        <b-form class="w-100 m-auto" @submit.prevent="handleSubmit(onSubmit)">
          <ValidationProvider v-slot="v" rules="required|username">
            <b-form-group label="Имя:" name="first_name">
              <b-form-input
                id="firstName"
                v-model.trim="user.first_name"
                type="text"
                placeholder="Введите имя"
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
            <b-form-group label="Email:" label-for="email">
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
              <p class="pt-3 text-muted">
                На этот email будет выслано письмо с активацией
              </p>
            </b-form-group>
          </ValidationProvider>
          <b-form-group>
            <p class="error-message">{{ errors[0] }}</p>
          </b-form-group>
          <div class="d-flex">
            <b-button type="submit" variant="primary" class="background-purple">
              Отправить письмо
            </b-button>
            <Loader v-show="loading" class="ml-4" />
          </div>
        </b-form>
      </ValidationObserver>
    </b-col>
  </b-row>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { mapActions } from 'vuex';
import Loader from '@/components/Loader';
export default {
  name: 'RegistrationForm',
  components: { ValidationObserver, ValidationProvider, Loader },
  data: () => ({
    user: {
      email: '',
      first_name: '',
    },
    errors: '',
    loading: false,
  }),
  methods: {
    ...mapActions(['setMessage']),
    async onSubmit() {
      this.loading = true;
      await this.$axios
        .post(`${process.env.api_url}/user/signup`, { data: this.user })
        .then(() => {
          this.setMessage(
            `На электронный адресс ${this.user.email} было выслано письмо с сылкой активации.`
          );
          this.$router.push('/');
        })
        .catch((error) => {
          if (error.response && error.response.data) {
            this.loading = false;
            this.errors = error.response.data.errors.email;
          }
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.error-message {
  color: $red;
}

button {
  border-color: $purple;
}
</style>
