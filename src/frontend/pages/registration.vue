<template>
  <b-container class="pt-5 pb-5 mt-4 h-100">
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
