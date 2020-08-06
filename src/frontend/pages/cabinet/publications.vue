<template>
  <b-container class="pt-3 pb-3">
    <Breadcrumbs />
    <b-row>
      <b-col cols="4">
        <CabinetNav />
      </b-col>
      <b-col>
        <div class="d-flex flex-row justify-content-between w-100 mb-4">
          <b-form-input
              v-model="searchText"
              class="mp-input search-field mr-2"
              placeholder="Введите название"
          />
          <b-button
              class="background-purple mp-button-purple mr-2"
              @click="$fetch"
          >
            ПОИСК
          </b-button>
          <b-button
              v-b-toggle.filter-collapse
              class="background-purple mp-button-purple collapse-button d-flex"
          >
            ФИЛЬТР
          </b-button>
        </div>
        <CardFilter containerClass="mt-3 d-flex flex-row justify-content-between align-items-center">
          <div class="d-flex flex-row align-items-center sorting-controls">
            <SortingButton
                class="ml-0 mr-3"
                text="По дате"
                @changed="sortByDate"
            />
            <SortingButton text="По просмотрам" @changed="sortByViews" />
            <b-form-select
                class="select-author"
                v-model="authorFilterValue"
                :options="authorOptions"
                @change="getPublications(publicationsApiParams)"
            />
          </div>
          <b-button
              pill
              class="background-purple clear-filter"
              @click="clearFilter"
          >
            Сбросить фильтр
          </b-button>
        </CardFilter>

        <div class="publication-rows">
          <PublicationsRow
            v-for="publication in publications"
            key="publication.id"
            :publication="publication"/>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import PublicationsRow from '@/components/cabinet/publications/PublicationsRow';
import publications from '@/mixins/publications';
import users from '@/mixins/users';
import { NewsModel, SortDirection } from '@/shared/constants';
import CardFilter from '@/components/CardFilter';
import SortingButton from '@/components/SortingButton';

export default {
  name: 'Publications',
  components: { CabinetNav, Breadcrumbs, PublicationsRow, CardFilter, SortingButton },
  mixins: [publications, users],
  middleware: ['auth'],
  async fetch() {
    await Promise.all([
      this.getMyPublications(this.publicationsApiParams, true),
      this.getUsers(),
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
  }
};
</script>

<style lang="scss">
.publication-rows {
  display: grid;
  grid-template-columns: auto;
  row-gap: 1.5625rem;
}
</style>
