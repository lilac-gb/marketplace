<template>
  <b-container class="vh-100 d-flex align-items-center justify-content-center">
    <div class="forms mt-n5">
        <ValidationObserver v-slot="{ handleSubmit }">
          <b-form @submit.prevent="handleSubmit(onSubmit)">
            <h2 class="mb-3">Восстановление пароля</h2>
            <ValidationProvider v-slot="{ errors }" rules="required|email" name="email">
              <b-form-group label="Email" label-for="email">
                <b-form-input
                  id="email"
                  v-model="email"
                  type="email"
                  placeholder="Введите email"
                ></b-form-input>
                <b-form-invalid-feedback :class="{ 'd-block': error }">
                  {{ error }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback :class="{ 'd-block': errors }">
                  {{ errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </ValidationProvider>
            <div class="d-flex justify-content-between align-items-center">
              <b-button type="submit" variant="secondary" class="background-purple">
                Отправить
              </b-button>
              <b-link class="text-muted" :to="`/login`">Войти</b-link>
            </div>
            <Loader v-show="loading" />
          </b-form>
        </ValidationObserver>
    </div>
  </b-container>
</template>

<script>
  import config from '@/config';
  import { ValidationObserver, ValidationProvider } from 'vee-validate';
  import { mapActions } from 'vuex';
  import Loader from '@/components/Loader';

  export default {
    components: { ValidationObserver, ValidationProvider, Loader },
    data() {
      return {
        email: '',
        loading: false,
        error: false,
      };
    },
    methods: {
      ...mapActions(['setMessage']),
      async onSubmit() {
        this.loading = true;
        this.$axios.post(`${config.api_url}/user/restore-password`, { email: this.email }).then((response) => {
          if (response.data && response.data.statusCode === 200) {
            this.setMessage(`На электронный адресс ${this.email} было выслано письмо с дальнейшими инструкциями.`);
            this.loading = false;
            this.$router.push('/');
          }
        }).catch((error) => {
          if (error.response && error.response.data) {
            this.loading = false;
            this.error = error.response.data.errors.email;
          }
        });
      },
    },
    head() {
      return {
        title: 'Password recovery',
      };
    },
    layout: 'default',
  };
</script>

