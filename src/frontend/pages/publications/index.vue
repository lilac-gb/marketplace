<template>
  <b-container class="mt-4 mb-4" id="publications">
    <div class="publications-grid">
      <publication-card
        v-for="publication in publications"
        :key="publication.id"
        :publication="publication"/>
    </div>
  </b-container>
</template>

<script>
import { appDomen } from '@/shared/constants';
import PublicationsCard from '@/pages/publications/card';

export default {
  name: "publications",
  components: {
    'publication-card': PublicationsCard
  },
  data() {
    return {
      publications: []
    }
  },
  async fetch() {
   let result = await this.$http.$get(`${appDomen}/news?expand=_metaTags`);
   this.publications = result.data.models;
  }
}
</script>

<style lang="scss">
  #publications {
    .publications-grid {
      display: grid;
      grid-template-columns: repeat(3, 380px);
      grid-template-rows: auto;
      grid-column-gap: 20px;
      grid-row-gap: 20px;
    }
  }
</style>