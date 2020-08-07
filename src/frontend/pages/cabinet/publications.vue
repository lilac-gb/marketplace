<template>
  <b-container class="cabinet-publications mt-4 mb-4">
    <Breadcrumbs />
    <b-row>
      <b-col cols="3">
        <CabinetNav />
      </b-col>
      <b-col>
        <div class="filter">
          <div class="d-flex flex-row justify-content-between w-100 mb-4">
            <b-form-input
                v-model="searchText"
                class="mp-input search-field mr-2"
                placeholder="Введите название"/>
            <b-button
                class="mr-2 mp-btn-transparent background-white"
                @click="$fetch">
              Поиск
            </b-button>
            <b-button class="background-purple mp-button-purple">
              Создать публикацю
            </b-button>
          </div>
          <CardFilter
            containerClass="d-flex flex-row justify-content-between align-items-center"
            :headerText="null">
            <div class="d-flex flex-row align-items-center sorting-controls">
              <SortingButton
                  class="ml-0 mr-3"
                  text="По дате"
                  @changed="sortByDate"/>
              <SortingButton text="По просмотрам" @changed="sortByViews"/>
            </div>
            <div>
              <b-form-select
                class="status-select mr-2"
                v-model="status"
                :options="newsStatusesNames"
                @change="$fetch">
                <template v-slot:first>
                  <b-form-select-option :value="null">Все</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-select
                class="size-select"
                v-model="perPage"
                :options="paginationSize"
                @change="$fetch"/>
            </div>
          </CardFilter>
        </div>

        <div class="publication-rows">
          <PublicationsRow
            v-for="publication in publications"
            key="publication.id"
            :publication="publication"
            @updated="$fetch"/>
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

const newsStatusesNames = [
  { value: 0, text: 'Редактирование' },
  { value: 1, text: 'Опубликовано' },
  { value: 2, text: 'Модерация' },
];

const paginationSize = [
  5,
  10,
  25,
  50,
];

export default {
  name: 'Publications',
  components: { CabinetNav, Breadcrumbs, PublicationsRow, CardFilter, SortingButton },
  mixins: [publications, users],
  middleware: ['auth'],
  async fetch() {
    await this.getMyPublications(this.publicationsApiParams, true);
  },
  data() {
    return {
      newsStatusesNames,
      paginationSize,
      publications: [],
      searchText: null,
      authorFilterValue: null,
      status: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 10,
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
      if (this.status !== null) {
        params['status'] = this.status;
      }
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
  },
};
</script>

<style lang="scss">
.cabinet-publications {
  .filter {
    input,
    button,
    select {
      font-size: 0.8125rem;
      line-height: 0.9375rem;
      height: 2.125rem;
    }

    button {
      white-space: nowrap;
      padding: 0 27px 0 27px;
      min-width: 10.375rem;
    }

    .status-select {
      width: 11rem;
    }

    .size-select {
      width: 6rem;
    }
  }

  .publication-rows {
    display: grid;
    grid-template-columns: auto;
    row-gap: 1.5625rem;
  }
}
</style>
