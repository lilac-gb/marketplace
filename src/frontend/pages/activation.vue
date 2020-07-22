<template id="activate">
  <h1>activation</h1>
</template>

<script>
import axios from 'axios';

let response;
export default {
  name: 'Activation',
  layout: 'default',
  async asyncData({query, store, redirect}) {
    if (query.hash && query.id) {
      try {
        await axios.post(
          `${process.env.api_url}/user/${query.id}/activation-email`,
          {hash: query.hash},
        ).then(response => {
          console.log(response.data.token);
          // TODO here we catch token and can login, but it not works
          // !!response && !!response.data && this.$auth.setUserToken(response.data.token);
        });
        store.commit('updateMessage', 'Email успешно подтвержден!');
        redirect('/cabinet');
      } catch (e) {
        let error = JSON.parse(e.request);
        store.commit('updateMessage', (error.response.data.message || 'Произошла ошибка запроса!'));
        redirect('/');
      }
    }
  },
};
</script>

<style lang="scss">
  #activate {
    height: 50vh;
  }
</style>
