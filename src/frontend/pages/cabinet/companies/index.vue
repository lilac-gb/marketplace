<template>
  <b-container class="py-3">
    <Breadcrumbs/>
    <b-row>
      <b-col lg="3" md="6" sm="6" xs="12">
        <CabinetNav/>
      </b-col>
      <b-col lg="9" md="6" sm="6" xs="12">
        <div class="filter">
          <div class="d-flex flex-row justify-content-between w-100 mb-4">
            <b-form-input
                v-model="searchText"
                class="mp-input search-field mr-2"
                placeholder="Введите название"
            />
            <b-button
                class="mr-2 mp-btn-transparent background-white"
                @click="$fetch">
              Поиск
            </b-button>
            <b-button class="background-purple mp-button-purple" @click="create">
              Создать компанию
            </b-button>
          </div>
        </div>

        <div class="companies-rows">
          <CompaniesRow
              v-for="company in companies"
              key="company.id"
              :company="company"
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
  import CompaniesRow from '@/components/cabinet/companies/CompaniesRow'
  import {constructUrl} from '@/shared/api';
  import companies from '@/mixins/companies';
  import config from '@/config';

  const paginationSize = [
    5,
    10,
    25,
    50,
  ];

  export default {
    name: 'Index',
    middleware: ['auth'],
    mixins: [companies],
    components: {CabinetNav, Breadcrumbs, CompaniesRow},
    data() {
      return {
        searchText: null,
        companies: [],
        paginationSize,
        status: null,
        currentPage: 1,
        pageCount: 1,
        perPage: 10,
        totalCount: null,
      }
    },
    async fetch() {
      await this.getMyCompanies(this.companyApiParams, true);
    },
    computed: {
      companyApiParams() {
        let params = {
          page: this.currentPage,
          pageSize: this.perPage,
        };
        if (this.status !== null) {
          params['status'] = this.status;
        }
        if (this.searchText) {
          params['Company[name]'] = this.searchText;
        }
        return params;
      }
    },
    methods: {
      fetchMoreItems() {
        this.perPage += 12;
        this.$fetch();
      },
      create() {
        this.$router.push({name: 'cabinet-companies-company_id', params: {company_id: 'new'}});
      }
    },
  };
</script>

<style lang="scss">
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
  }
</style>
