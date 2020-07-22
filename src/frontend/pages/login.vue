<template>
  <b-container class="pt-5 pb-5 mt-4 h-100">
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
