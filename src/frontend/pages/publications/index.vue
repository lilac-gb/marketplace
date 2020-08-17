<template>
  <section id="publications" class="min-vh-100">
    <div v-if="loading" class="main-loader">
      <Loader />
    </div>
    <b-container class="mt-4 mb-4">
      <Breadcrumbs :items="breadcrumbs" class="d-flex" />
      <b-row class="mb-4">
        <b-col lg="8" md="6">
          <b-form-input
            v-model="searchText"
            class="search-field mr-2"
            placeholder="Введите название"
          ></b-form-input>
        </b-col>
        <b-col lg="2" md="3">
          <b-button
            class="mp-btn mp-btn-purple w-100"
            @click="$fetch"
          >
          ПОИСК
        </b-button>
        </b-col>
        <b-col lg="2" md="3">
          <b-button
            class="mp-btn mp-btn-purple collapse-button d-flex align-items-center justify-content-center w-100"
            v-b-toggle.filter-collapse
          >
          ФИЛЬТР
        </b-button>
        </b-col>
      </b-row>
      <b-collapse id="filter-collapse" class="mt-2 mb-4">
        <b-row>
          <b-col lg="2">
            <SortingButton
              text="По дате"
              @changed="sortByDate"
            />
            </b-col>
          <b-col lg="2">
            <SortingButton
              text="По просмотрам"
              @changed="sortByViews"
            />
            </b-col>
             <b-col lg="5">
            <b-form-select
              class="select-author"
              v-model="authorFilterValue"
              :options="authorOptions"
              @change="getPublications(publicationsApiParams)"
            />
            </b-col>
          <b-col lg="3" class="d-flex justify-content-end align-items-center">
            <b-button
              pill
              class="mp-btn mp-btn-purple pull-right"
              @click="clearFilter"
            >
            Сбросить фильтр
          </b-button>
          </b-col>
        </b-row>
      </b-collapse>
      
      <div class="publications-grid">
        <PublicationsCard
          v-for="publication in publications"
          :key="publication.id"
          :publication="publication"
        />
      </div>

      <div
        v-if="!!searchText && !publications.length && !loading"
        class="text-muted mb-4 mt-4 d-flex justify-content-center"
      >
        Ничего не найдено, попробуйте изменить запрос
      </div>
      
      <div
        v-if="!searchText && !publications.length && !loading"
        class="text-muted mb-4 mt-4 d-flex justify-content-center"
      >
        Ничего не найдено, попробуйте изменить фильтрацию
      </div>

      <div v-if="totalCount > perPage" class="mb-4 mt-4 d-flex justify-content-center">
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
  </section>
</template>

<script>
import PublicationsCard from '@/components/publications/card';
import Breadcrumbs from '@/components/Breadcrumbs';
import SortingButton from '@/components/SortingButton';
import Loader from '@/components/Loader';
import { ModelParams, SortDirection } from '@/shared/constants';
import publications from '@/mixins/publications';
import users from '@/mixins/users';
import { getFullName } from '@/shared/utils';

export default {
  name: 'Publications',
  components: {
    Breadcrumbs,
    PublicationsCard,
    SortingButton,
    Loader,
  },
  mixins: [publications, users],
  async fetch() {
    this.loading = true;
    await Promise.all([
      this.getPublications(this.publicationsApiParams, true),
      this.getUsers(),
    ]);
    this.breadcrumbs = [
      { label: 'Публикации', url: null },
    ];
    this.loading = false;
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
      sortBy: ModelParams.CREATED_AT,
      sortDesc: SortDirection.ASK,
      loading: false,
      breadcrumbs: [],
    };
  },
  computed: {
    authorOptions() {
      let result = [{ text: 'Все пользователи', value: null }];
      this.users.map((user) => result.push({
        value: user.id,
        text: getFullName(user),
      }))
      
      return result;
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
    },
  },
  methods: {
    fetchMoreItems() {
      this.perPage += 12;
      this.$fetch();
    },
    sortByDate(direction) {
      this.sortBy = ModelParams.CREATED_AT;
      this.sortDesc = direction;
      this.$fetch();
    },
    sortByViews(direction) {
      this.sortBy = ModelParams.VIEWS;
      this.sortDesc = direction;
      this.$fetch();
    },
    async clearFilter() {
      this.sortBy = ModelParams.CREATED_AT;
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
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-template-rows: auto;
    grid-column-gap: 20px;
    grid-row-gap: 20px;
  }
  
  @media screen and (max-width: 786px) {
    .publications-grid {
      display: grid;
      grid-template-columns: 1fr;
    }
  }
  
  @media screen and (max-width: 900px) {
    .publications-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }
  }
  
  @media screen and (max-width: 1200px) {
    .publications-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
    }
  }
  
  .select-author {
    margin-bottom: -35px;
  }
}
</style>
