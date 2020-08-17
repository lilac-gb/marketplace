<template>
  <b-container class="vh-100 d-flex align-items-center justify-content-center">
    <LoginForm />
  </b-container>
</template>

<script>
import LoginForm from '@/components/forms/LoginForm';

export default {
  name: 'Login',
  components: { LoginForm },
  middleware: ['auth'],
  auth: 'guest',
  layout: 'default',
};
</script>
