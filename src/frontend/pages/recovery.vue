<template>
  <b-container>
    <b-row style="min-height: 48rem;" class="mt-5">
      <b-col>
        <ValidationObserver v-slot="{ handleSubmit }">
          <b-form class="w-50 m-auto" @submit.prevent="handleSubmit(onSubmit)">
            <h1 class="mb-5">Восстановление пароля</h1>
            <ValidationProvider v-slot="{ errors }" rules="required|email" name="email">
              <b-form-group label="Email" label-for="email">
                <b-form-input
                  id="email"
                  v-model="email"
                  type="email"
                  placeholder="Введите email"
                />
                <b-form-invalid-feedback :class="{ 'd-block': errors }">
                  {{ errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </ValidationProvider>
            <b-button type="submit" variant="primary">Отправить</b-button>
          </b-form>
        </ValidationObserver>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import config from '@/config';
import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
  components: { ValidationObserver, ValidationProvider },
  data() {
    return {
      email: '',
    };
  },
  methods: {
    async onSubmit() {
      try {
        await this.$axios
          .post(`${config.api_url}/user/restore-password`, {
            email: this.email,
          })
          .then((response) => console.log(response));
          // if (response.status === 200) {
          //   this.$router.push('/');
          // } else {
          //   console.log(response.error);
          // }
      } catch (e) {
        console.log(e.data);
      }
    },
  },
  head() {
    return {
      title: 'Password recovery',
    };
  },
  layout: 'empty',
};
</script>

<style scoped lang="scss">
.row {
  min-height: 48rem;
}
</style>
