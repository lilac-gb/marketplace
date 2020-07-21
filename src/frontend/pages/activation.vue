<template>
  <h1>activation</h1>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Activation',
  layout: 'empty',
  async validate({ query, store, redirect }) {
    if (query.hash) {
      try {
        const response = await axios.post(
          `${process.env.api_url}/user/activation-email`,
          {
            header: { Accept: 'application/json ' },
            data: { hash: query.hash },
          }
        );
        console.log(response);
        store.commit('updateMessage', 'Email успешно подтвержден.');
      } catch (e) {
        store.commit('updateMessage', 'Произошла ошибка!');
        redirect('/');
      }
    }
  },
};
</script>
