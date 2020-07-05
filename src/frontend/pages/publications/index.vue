<template>
  <b-container class="mt-4 mb-4 mp-container" id="publications">
    <div class="d-flex flex-row justify-content-between w-100 mb-4">
      <b-form-input class="mp-input search-field" v-model="searchText" placeholder="Введите название"></b-form-input>
      <b-button class="background-purple mp-button" @click="$fetch">ПОИСК</b-button>
      <b-button class="background-purple mp-button">ФИЛЬТР</b-button>
    </div>
    <div class="publications-grid">
      <publication-card
        v-for="publication in publications"
        :key="publication.id"
        :publication="publication"/>
    </div>
  </b-container>
</template>

<script>
import PublicationsCard from '@/pages/publications/card';
import config from "@/config";
import { constructUrl } from '@/shared/api';

export default {
  name: "publications",
  components: {
    'publication-card': PublicationsCard
  },
  data() {
    return {
      publications: [],
      searchText: null
    }
  },
  async fetch() {
    let params = {expand: '_metaTags'};
    if (this.searchText) {
      params['News[name]'] = this.searchText;
    }
    let result = await this.$http.$get(constructUrl(`${config.api_url}/news`, params));
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

    .search-field {
      width: 760px;
    }
  }
</style>