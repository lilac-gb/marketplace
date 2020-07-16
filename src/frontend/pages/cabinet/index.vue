<template>
  <b-container class="pt-3 pb-3">
    <h1 class="text-center">Добро пожаловать, {{ fullUserName }}</h1>
  </b-container>
</template>
<script>
export default {
  middleware: ['auth'],
  async fetch() {
    try {
      this.user = await this.$auth.user;
    } catch (e) {
      console.log(e);
    }
  },
  data: () => ({
    user: {},
  }),
  computed: {
    fullUserName() {
      return this.user.first_name + ' ' + this.user.last_name;
    },
  },
};
</script>
