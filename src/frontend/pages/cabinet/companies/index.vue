<template>
  <b-container class="py-3">
    <Breadcrumbs />
    <b-row>
      <b-col lg="3" md="6" sm="6" xs="12">
        <CabinetNav />
      </b-col>
      <h1>тута компании</h1>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import { constructUrl } from '@/shared/api';
import config from '@/config';
export default {
  name: 'Index',
  components: { CabinetNav, Breadcrumbs },
  async fetch() {
    let params = { expand: '_metaTags' };
    this.$http.setToken(this.$auth.getToken('local'));
    let response = await this.$axios.$get(
      constructUrl(`${config.api_url}/company/view`, params)
    );
    this.$http.setToken(false);
    console.log(response.data);
    const { name, description, _metaTags } = response.data;
    this.name = name;
    this.description = description;
    this.metaTags = _metaTags;
  },
};
</script>

<style scoped></style>
