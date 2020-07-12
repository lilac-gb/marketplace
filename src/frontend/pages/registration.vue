<template>
  <b-container>
    <RegistrationForm />
  </b-container>
</template>

<script>
import RegistrationForm from '@/components/Forms/RegistrationForm';

export default {
  name: 'Registration',
  layout: 'empty',
  components: { RegistrationForm },
};
</script>

<style scoped></style>
