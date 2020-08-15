<template>
  <b-container class="pt-3 pb-3">
    <Breadcrumbs :items="breadcrumbs" />
    <b-row>
      <b-col lg="2" md="2" sm="3" xs="4">
        <CabinetNav />
      </b-col>
      <b-col lg="10" md="10" sm="9" xs="8">
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
    this.breadcrumbs = [
      { label: 'Кабинет', url: null },
    ];
  },
  data: () => ({
    user: {},
    breadcrumbs: [],
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
