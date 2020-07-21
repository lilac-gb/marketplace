<template>
  <b-container id="publications" class="mt-4 mb-4 mp-container">
    <div class="d-flex flex-row justify-content-between w-100 mb-4">
      <b-form-input
        v-model="searchText"
        class="mp-input search-field"
        placeholder="Введите название"
      />
      <b-button
        class="background-purple mp-button-purple"
        @click="$fetch">ПОИСК</b-button>
      <b-button
        v-b-toggle.filter-collapse
        class="background-purple mp-button-purple collapse-button">ФИЛЬТР</b-button>
    </div>
    <b-collapse id="filter-collapse" class="mt-2">
      <filter-card containerClass="mt-3 d-flex flex-row justify-content-between align-items-center">
        <div class="d-flex flex-row align-items-center sorting-controls">
          <sorting-button
            class="ml-0 mr-3"
            text="По дате"
            @changed="sortByDate"/>
          <sorting-button text="По просмотрам" @changed="sortByViews" />
          <b-form-select class="select-author" v-model="authorFilterValue" :options="authorOptions" @change="getPublications(publicationsApiParams)"></b-form-select>
        </div>
        <b-button pill class="background-purple clear-filter" @click="clearFilter">Сбросить фильтр</b-button>
      </filter-card>
    </b-collapse>

    <div class="publications-grid">
      <publication-card
        v-for="publication in publications"
        :key="publication.id"
        :publication="publication"/>
    </div>

    <div class="mb-4 mt-4 d-flex justify-content-center">
      <b-link
        class="background-white text-purple mp-button-white mr-4 page-link"
        @click="fetchMoreItems"
      >
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
        @input="$fetch"
      />
    </div>
  </b-container>
</template>

<script>
import PublicationsCard from '@/components/publications/card';
import CardFilter from '@/components/CardFilter';
import SortingButton from '@/components/SortingButton';
import config from '@/config';
import { NewsModel, SortDirection } from '@/shared/constants';
import publications from '@/mixins/publications';
import { getFullName } from '@/shared/utils';

export default {
  name: 'Publications',
  components: {
    'publication-card': PublicationsCard,
    'filter-card': CardFilter,
    'sorting-button': SortingButton,
  },
  mixins: [
    publications
  ],
  async fetch() {
    await Promise.all([
      this.getPublications(this.publicationsApiParams),
      this.getUsers()
    ]);
  },
  data() {
    return {
      publications: [],
      users: [],
      searchText: null,
      authorFilterValue: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 12,
      totalCount: null,
      sortBy: NewsModel.CREATED_AT,
      sortDesc: SortDirection.ASK,
    };
  },
  computed: {
    authorOptions() {
      return this.users.map(u => {
        return {
          value: u.id,
          text: getFullName(u)
        }
      });
    },
    publicationsApiParams() {
      let params = {
        page: this.currentPage,
        pageSize: this.perPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      };
      if (this.searchText) {
        params['News[name]'] = this.searchText;
      }
      if (this.authorFilterValue) {
        params['user_id'] = this.authorFilterValue;
      }
      return params;
    }
  },
  methods: {
    async getUsers() {
      let result = await this.$http.$get(`${config.api_url}/user`);
      this.users = result.data.models;
    },
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
    },
    async clearFilter() {
      this.sortBy = NewsModel.CREATED_AT;
      this.sortDesc = SortDirection.ASK;
      this.authorFilterValue = null;
      await this.getPublications(this.publicationsApiParams);
    },
  },
};
</script>

<style lang="scss" scoped>
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
    width: fit-content;
  }

  .select-author {
    width: 476px;
    margin-left: 29px;
  }

  .clear-filter {
    width: 161px;
    height: 31px;
  }
}
</style>
