<template>
  <section id="cabinet-companies" class="min-vh-100">
    <div v-if="loading" class="main-loader">
      <Loader />
    </div>
    <b-container class="py-3">
    <Breadcrumbs :items="breadcrumbs" />
    <b-row>
      <b-col lg="2" md="2" sm="3" xs="4">
        <CabinetNav />
      </b-col>
      <b-col lg="10" md="10" sm="9" xs="8">
        <div class="filter">
          <div class="d-flex flex-row justify-content-between w-100 mb-4">
            <b-form-input
              v-model="searchText"
              class="mp-input search-field mr-2"
              placeholder="Введите название"
            ></b-form-input>
            <b-button
              class="mr-2 mp-btn-transparent background-white"
              @click="$fetch"
            >
              Поиск
            </b-button>
            <b-button class="background-purple mp-button-purple" @click="create">
              Создать компанию
            </b-button>
          </div>
          
          <div class="d-flex flex-row align-items-center justify-content-between mb-4">
          <div class="d-flex">
            <SortingButton
              class="ml-0 mr-3"
              text="По дате"
              @changed="sortByDate"
            />
            <SortingButton text="По просмотрам" @changed="sortByViews" />
          </div>
          <div class="d-flex justify-content-end">
            <b-form-select
              class="status-select mr-2"
              v-model="status"
              :options="ModelStatusesNames"
              @change="$fetch"
            >
              <template v-slot:first>
                <b-form-select-option :value="null">Все</b-form-select-option>
              </template>
            </b-form-select>
            <b-form-select
              class="size-select"
              v-model="perPage"
              :options="paginationSize"
              @change="$fetch"
            ></b-form-select>
          </div>
        </div>
        </div>

        <div class="companies-rows">
          <CompaniesRow
            v-for="company in companies"
            :key="company.id"
            :company="company"
            @updated="$fetch"
          />
        </div>
        <div v-if="!totalCount" class="mb-4 mt-4 d-flex justify-content-center text-muted">
          У Вас пока нет компаний, начните создавать
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
          ></b-pagination>
        </div>
      </b-col>
    </b-row>
  </b-container>
  </section>
</template>

<script>
  import Breadcrumbs from '@/components/Breadcrumbs';
  import Loader from '@/components/Loader';
  import CabinetNav from '@/components/cabinet/CabinetNav';
  import CompaniesRow from '@/components/cabinet/companies/CompaniesRow';
  import { ModelParams, SortDirection } from '@/shared/constants';
  import SortingButton from '@/components/SortingButton';
  import { constructUrl } from '@/shared/api';
  import companies from '@/mixins/companies';

  const paginationSize = [
    5,
    10,
    25,
    50,
  ];

  export default {
    name: 'Index',
    components: {
      CabinetNav,
      Breadcrumbs,
      CompaniesRow,
      SortingButton,
      Loader,
    },
    mixins: [companies],
    data() {
      return {
        ModelStatusesNames: [],
        searchText: null,
        companies: [],
        paginationSize,
        status: null,
        currentPage: 1,
        pageCount: 1,
        perPage: 10,
        totalCount: null,
        breadcrumbs: [],
        loading: false,
      };
    },
    computed: {
      companyApiParams() {
        let params = {
          page: this.currentPage,
          pageSize: this.perPage,
        };
        if (this.status !== null) {
          params.status = this.status;
        }
        if (this.searchText) {
          params.search = this.searchText;
        }
        return params;
      },
    },
    methods: {
      create() {
        this.$router.push({ name: 'cabinet-companies-company_id', params: { company_id: 'new' } });
      },
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
    },
    middleware: ['auth'],
    async fetch() {
      this.loading = true;
      await this.getMyCompanies(this.companyApiParams, true);
      this.breadcrumbs = [
        { label: 'Кабинет', url: '/cabinet' },
        { label: 'Компании', url: null },
      ];
      this.loading = false;
    },
  };
</script>

<style lang="scss">
  .filter {
    .size-select {
      width: 6rem;
    }
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
  }
</style>
