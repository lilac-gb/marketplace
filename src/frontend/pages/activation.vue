<template>
  <b-container class="pt-5 pb-5 mt-4 h-100">
    <div class="d-flex align-items-center justify-content-center w-100">
      <h1>Подождите идет активация...</h1>
    </div>
  </b-container>
</template>

<script>
  import axios from 'axios';
  export default {
    name: 'Activation',
    layout: 'default',
    fetch({query, store, redirect}) {
      if (query.hash && query.id) {
        axios.post(`${process.env.api_url}/user/${query.id}/activation-email`, {hash: query.hash})
          .then(response => {
            if (response.data && response.data.token) {
              store.$auth.setUserToken(response.data.token);
              store.commit('updateMessage', 'Email успешно подтвержден! Временный пароль высдан Вам на email');
              redirect('/cabinet');
            }
          })
          .catch(error => {
            if (error.response && error.response.data) {
              store.commit('updateMessage', error.response.data.data.message);
              redirect('/');
            }
          });
      }
    },
  };
</script>
