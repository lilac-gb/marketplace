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
              <p class="pt-3">
                На этот email будет выслано письмо с активацией
              </p>
            </b-form-group>
          </ValidationProvider>
          <b-form-group>
            <p class="error-message">{{ error }}</p>
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
      last_name: '',
    },
    error: '',
    loading: false,
  }),
  methods: {
    ...mapActions(['setMessage']),
    async onSubmit() {
      try {
        this.loading = true;
        const response = await this.$axios.post(
          `${process.env.api_url}/user/signup`,
          {
            headers: { Accept: 'application/json' },
            data: this.user,
          }
        );

        if (response.data.success) {
          this.setMessage(
            `На электронный адресс ${this.user.email} было выслано письмо с активацией.`
          );
          await this.$router.push('/');
        }
      } catch (e) {
        this.loading = false;
        this.error = 'Что то пошло не так. Пожалуйста, повторите попытку позже';
      }
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
