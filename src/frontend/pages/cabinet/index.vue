<template>
  <b-container class="pt-3 pb-3">
    <Breadcrumbs />
    <b-row>
      <b-col cols="4">
        <CabinetNav />
      </b-col>
      <b-col>
        <h1 class="text-center">Добро пожаловать, {{ fullUserName }}</h1>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
export default {
  components: { CabinetNav, Breadcrumbs },
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
      return `${this.user.first_name}${
        this.user.last_name ? ` ${this.user.last_name}` : ''
      }`;
    },
  },
};
</script>
