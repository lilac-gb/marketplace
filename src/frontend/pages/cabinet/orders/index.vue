<template>
  <section id="cabinet-orders" class="min-vh-100">
    <div v-if="loading" class="main-loader">
      <Loader />
    </div>
    <b-container class="cabinet-orders py-3">
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
              class="mp-btn-transparent background-white"
              @click="$fetch"
            >
              Поиск
            </b-button>
          </div>
          
            <div class="d-flex flex-row align-items-center justify-content-between mb-4">
              <div class="d-flex">
                <SortingButton
                  class="ml-0 mr-3"
                  text="По дате"
                  @changed="sortByDate"
                />
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

        <div class="order-rows">
          <OrdersRow
            v-for="order in orders"
            :key="order.id"
            :order="order"
            @updated="$fetch"
          />
        </div>
        <div v-if="!totalCount" class="mb-4 mt-4 d-flex justify-content-center text-muted">
          У Вас пока нет заказов, чтобы создать, добавьте товары в корзину и начните оформление
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
    import OrdersRow from '@/components/cabinet/orders/OrdersRow';
  import Breadcrumbs from '@/components/Breadcrumbs';
  import CabinetNav from '@/components/cabinet/CabinetNav';
  import orders from '@/mixins/orders';
  import Loader from '@/components/Loader';
  import { ModelParams, SortDirection } from '@/shared/constants';
  import SortingButton from '@/components/SortingButton';

  const ModelStatusesNames = [
    { value: 0, text: 'Оформление' },
    { value: 1, text: 'Выполнено' },
    { value: 2, text: 'Доставка' },
  ];

  const paginationSize = [
    5,
    10,
    25,
    50,
  ];

  export default {
    name: 'index',
    middleware: ['auth'],
    mixins: [orders],
    components: {
      CabinetNav,
      Breadcrumbs,
      OrdersRow,
      SortingButton,
      ModelParams,
      SortDirection,
      Loader,
    },
    async fetch() {
      this.loading = true;
      await this.getMyOrders(this.ordersApiParams, true);
      this.breadcrumbs = [
        { label: 'Кабинет', url: '/cabinet' },
        { label: 'Заказы', url: null },
      ];
      this.loading = false;
    },
    data: () => ({
      ModelStatusesNames,
      paginationSize,
      orders: [],
      searchText: null,
      status: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 10,
      totalCount: null,
      sortBy: ModelParams.CREATED_AT,
      sortDesc: SortDirection.ASK,
      breadcrumbs: [],
      loading: false,
    }),
    ordersApiParams() {
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
        params['Order[name]'] = this.searchText;
      }
      return params;
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
    },
  };
</script>

<style lang="scss">
.cabinet-orders {
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
  
  .order-rows {
    display: grid;
    grid-template-columns: auto;
    row-gap: 1.5625rem;
  }
}
</style>
