<template>
  <b-container class="wh-100 d-flex align-items-center justify-content-center">
      <h1>Подождите идет проверка...</h1>
  </b-container>
</template>

<script>
import axios from 'axios';
import config from '../config';

export default {
  name: 'Confirmation',
  layout: 'default',
  fetch({ query, store, redirect }) {
    if (query.hash && query.id) {
      axios.post(`${config.api_url}/user/${query.id}/confirm-email`, {
        hash: query.hash,
      }).then((response) => {
        if (response.data && response.data.statusCode === 200) {
          store.commit('updateMessage', 'Email успешно подтвержден!');
          redirect('/cabinet');
        }
      }).catch((error) => {
        if (error.response && error.response.data) {
          store.commit('updateMessage', error.response.data.data.message);
          redirect('/');
        }
      });
    }
  },
};
</script>
