<template>
  <b-container class="mt-4 mb-4 mp-container" id="publications">
    <div class="d-flex flex-row justify-content-between w-100 mb-4">
      <b-form-input class="mp-input search-field" v-model="searchText" placeholder="Введите название"></b-form-input>
      <b-button class="background-purple mp-button-purple" @click="$fetch">ПОИСК</b-button>
      <b-button class="background-purple mp-button-purple">ФИЛЬТР</b-button>
    </div>

    <div class="publications-grid">
      <publication-card
        v-for="publication in publications"
        :key="publication.id"
        :publication="publication"/>
    </div>

    <div class="mb-4 mt-4 d-flex justify-content-center">
      <b-link class="background-white text-purple mp-button-white mr-4 page-link" @click="fetchMoreItems">ЗАГРУЗИТЬ ЕЩЕ</b-link>
      <b-pagination
        class="mp-pagination"
        v-model="currentPage"
        :per-page="perPage"
        :total-rows="totalCount"
        first-number
        last-number
        size="lg"
        @input="$fetch"/>
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
      searchText: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 12,
      totalCount: null
    }
  },
  async fetch(add = false) {
    let params = {expand: '_metaTags', page: this.currentPage, pageSize: this.perPage};
    if (this.searchText) {
      params['News[name]'] = this.searchText;
    }
    let result = await this.$http.$get(constructUrl(`${config.api_url}/news`, params));
    this.publications = result.data.models;
    this.currentPage = result.data._meta.currentPage;
    this.pageCount = result.data._meta.pageCount;
    this.perPage = result.data._meta.perPage;
    this.totalCount = result.data._meta.totalCount;
  },
  methods: {
    fetchMoreItems() {
      this.perPage += 12;
      this.$fetch();
    }
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