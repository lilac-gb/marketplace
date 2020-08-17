<template>
  <b-container class="vh-100 d-flex align-items-center justify-content-center">
    <RegistrationForm />
  </b-container>
</template>

<script>
import RegistrationForm from '@/components/forms/RegistrationForm';

export default {
  name: 'Registration',
  layout: 'default',
  components: { RegistrationForm },
  middleware: ['auth'],
  auth: 'guest',
};
</script>
