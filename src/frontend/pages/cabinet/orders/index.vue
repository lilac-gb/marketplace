<template>
  <b-container class="py-3 vh-100">
    <Breadcrumbs :items="breadcrumbs" />
    <b-row>
      <b-col lg="2" md="2" sm="3" xs="4">
        <CabinetNav />
      </b-col>
      <b-col lg="10" md="10" sm="9" xs="8">
      
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
  import Breadcrumbs from '@/components/Breadcrumbs';
  import CabinetNav from '@/components/cabinet/CabinetNav';

  export default {
    name: 'index',
    middleware: ['auth'],
    components: { CabinetNav, Breadcrumbs },
    async fetch() {
      this.breadcrumbs = [
        { label: 'Кабинет', url: '/cabinet' },
        { label: 'Заказы', url: null },
      ];
    },
    data: () => ({
      breadcrumbs: [],
    }),
  };
</script>

<style scoped>

</style>
