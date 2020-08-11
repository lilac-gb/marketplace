<template>
  <b-container class="pt-3 pb-3">
    <Breadcrumbs />
    <b-row>
      <b-col lg="3" md="6" sm="6" xs="12">
        <CabinetNav />
      </b-col>
      <b-col>
        <div class="p-3 border bg-light">Тута объявки</div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '../../components/Breadcrumbs';
import CabinetNav from '../../components/cabinet/CabinetNav';
export default {
  name: 'Ads',
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
      return this.user.first_name + ' ' + this.user.last_name;
    },
  },
};
</script>

<style lang="scss" scoped></style>
