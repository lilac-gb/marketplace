<template>
  <b-container class="vh-100 d-flex align-items-center justify-content-center">
      <h1>Подождите идет проверка...</h1>
  </b-container>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'Restore',
    layout: 'default',
    fetch({ query, store, redirect }) {
      if (query.token) {
        axios.post(`${process.env.api_url}/user/receive-password`, { token: query.token })
          .then((response) => {
            console.log(response);
            if (response.data) {
              store.commit('updateMessage', 'Ваш новый пароль выслан Вам на email!');
              redirect('/');
            }
          })
          .catch((error) => {
            if (error.response && error.response.data) {
              store.commit('updateMessage', error.response.data.errors[0]);
              redirect('/');
            }
          });
      } else {
        store.commit('updateMessage', 'Нет токена!');
        redirect('/');
      }
    },
  };
</script>
