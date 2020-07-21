<template>
  <b-container>
    <b-row style="min-height: 48rem;" class="mt-5">
      <b-col>
        <div class="d-flex justify-content-center">
          <h2 class="mx-auto mb-5">Восстановление пароля</h2>
        </div>
        <b-form class="w-50 m-auto" @submit.prevent="onSubmit">
          <b-form-group label="Email" label-for="email">
            <b-form-input
              id="email"
              v-model="email"
              type="email"
              placeholder="Введите email"
            ></b-form-input>
          </b-form-group>
          <b-button type="submit" variant="primary">Отправить</b-button>
        </b-form>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
// import config from '@/config';
// import { constructUrl } from '@/shared/api';

export default {
  data() {
    return {
      email: '',
    };
  },
  methods: {
    async onSubmit() {
      console.log(this.email);
      // https://api.marketplace.docker/user/restore-password передать нужно email
      // let response = await this.$http.post(
      //   // (url: Request | URL | string, options?: Omit<Options, 'body'>)
      //   // constructUrl(`${config.api_url}/user/restore-password`, this.email)
      //   // (url: Request | URL | string, body?: RequestBody, options?: Options)
      //   constructUrl(`${config.api_url}/user/restore-password`, this.email)
      // );
      // // email попадает в v1/controllers/UserController.php actionRecovery()
      // if (response.ok) {
      //   // если сервер отправил письмо и вернул 'statusCode' => 200
      //   this.$router.push('/');
      // } else {}
    },
  },
  head() {
    return {
      title: 'Password recovery',
    };
  },
};
</script>

<style scoped lang="scss"></style>
