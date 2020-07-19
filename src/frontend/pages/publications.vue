<template>
  <b-container id="publications" class="mt-4 mb-4 mp-container">
    <div class="d-flex flex-row justify-content-between w-100 mb-4">
      <b-form-input
        v-model="searchText"
        class="mp-input search-field"
        placeholder="Введите название"/>
      <b-button class="background-purple mp-button-purple" @click="$fetch">ПОИСК</b-button>
      <b-button v-b-toggle.filter-collapse class="background-purple mp-button-purple collapse-button">ФИЛЬТР</b-button>
    </div>
    <b-collapse id="filter-collapse" class="mt-2">
      <filter-card>
        <div class="d-flex flex-row sorting-controls">
          <sorting-button class="ml-0 mr-3" text="По дате" @changed="sortByDate"/>
          <sorting-button text="По просмотрам" @changed="sortByViews"/>
        </div>
      </filter-card>
    </b-collapse>

    <div class="publications-grid">
      <publication-card
          v-for="publication in publications"
          :key="publication.id"
          :publication="publication"
      />
    </div>

    <div class="mb-4 mt-4 d-flex justify-content-center">
      <b-link
        class="background-white text-purple mp-button-white mr-4 page-link"
        @click="fetchMoreItems">
        ЗАГРУЗИТЬ ЕЩЕ
      </b-link>
      <b-pagination
        v-model="currentPage"
        class="mp-pagination"
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
import PublicationsCard from '@/components/publications/card';
import CardFilter from "@/components/CardFilter";
import SortingButton from "@/components/SortingButton";
import config from '@/config';
import { constructUrl } from '@/shared/api';
import { NewsModel, SortDirection } from '@/shared/constants';

export default {
  name: 'Publications',
  components: {
    'publication-card': PublicationsCard,
    'filter-card': CardFilter,
    'sorting-button': SortingButton,
  },
  async fetch() {
    let params = {
      expand: '_metaTags',
      page: this.currentPage,
      pageSize: this.perPage,
      sortBy: this.sortBy,
      sortDesc: this.sortDesc,
    };
    if (this.searchText) {
      params['News[name]'] = this.searchText;
    }
    let result = await this.$http.$get(
      constructUrl(`${config.api_url}/news`, params)
    );
    this.publications = result.data.models;
    this.currentPage = result.data._meta.currentPage;
    this.pageCount = result.data._meta.pageCount;
    this.perPage = result.data._meta.perPage;
    this.totalCount = result.data._meta.totalCount;
  },
  data() {
    return {
      publications: [],
      searchText: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 12,
      totalCount: null,
      sortBy: NewsModel.CREATED_AT,
      sortDesc: SortDirection.ASK,
    };
  },
  methods: {
    fetchMoreItems() {
      this.perPage += 12;
      this.$fetch();
    },
    sortByDate(direction) {
      this.sortBy = NewsModel.CREATED_AT;
      this.sortDesc = direction;
      this.$fetch();
    },
    sortByViews(direction) {
      this.sortBy = NewsModel.VIEWS;
      this.sortDesc = direction;
      this.$fetch();
    }
  },
};
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

  .sorting-controls {
    margin-top: 15px;
  }
}
</style>
