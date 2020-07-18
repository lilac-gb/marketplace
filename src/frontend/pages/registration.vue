<template>
  <b-container>
    <RegistrationForm />
  </b-container>
</template>

<script>
import RegistrationForm from '@/components/forms/RegistrationForm';

export default {
  name: 'Registration',
  layout: 'empty',
  components: { RegistrationForm },
  middleware: ['auth'],
  auth: 'guest',
};
</script>
