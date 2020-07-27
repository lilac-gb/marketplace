<template>
  <b-container>
    <b-row style="min-height: 48rem;" class="mt-5">
      <b-col>
        <ValidationObserver v-slot="{ valid }">
          <b-form class="w-50 m-auto" @submit.prevent="onSubmit">
            <h1 class="mb-5">Восстановление пароля</h1>
            <ValidationProvider v-slot="{ errors }" rules="required|email" name="email">
              <b-form-group label="Email" label-for="email">
                <b-form-input
                  id="email"
                  v-model="email"
                  type="email"
                  placeholder="Введите email"
                ></b-form-input>
                <span v-if="errors[0]">
                  {{ errors[0] }}
                </span>
              </b-form-group>
            </ValidationProvider>
            <b-button type="submit" variant="primary" :disabled="!valid">Отправить</b-button>
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
    onSubmit() {
      try {
        const response = this.$axios.post(`${config.api_url}/user/restore-password`,
          {
            email: this.email,
          }
        );
        console.log(response);
        // if (response.statusCode === 200) {
        //   // this.$router.push('/');
        // } else {
        //   console.log(response.error);
        // }
      } catch (e) {
        console.log(e);
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

<style scoped lang="scss"></style>
