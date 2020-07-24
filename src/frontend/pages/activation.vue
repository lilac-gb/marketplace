<template id="activate">
  <h1>activation</h1>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Activation',
  layout: 'empty',
  async asyncData({ query, store, redirect, params }) {
    if (query.hash && params.id) {
      try {
        const response = await axios.post(
          `${process.env.api_url}/user/${params.id}/activation-email`,
          {
            hash: query.hash,
          }
        );

        if (response.data.success) {
          store.commit('updateMessage', 'Email успешно подтвержден!');
          return { token: response.data.token };
        } else {
          store.commit('updateMessage', response.date.error || 'Произошла ошибка запроса!');
          redirect('/');
        }
      } catch (e) {
        let error = JSON.parse(e.request);
        store.commit(
          'updateMessage',
          error.response.data.message || 'Произошла ошибка запроса!'
        );
        redirect('/');
      }
    }
  },
  data: () => ({
    token: '',
  }),
  mounted() {
    this.$auth.loginWith('local', {
      data: { token: this.token },
    });
  },
};
</script>

<style lang="scss">
#activate {
  height: 50vh;
}
</style>
