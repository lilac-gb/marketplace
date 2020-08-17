<template>
  <section id="ads" class="min-vh-100">
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
        <div class="mt-3">
          <b-row>
            <b-col lg="4">
              <SortingButton
                text="По просмотрам"
                @changed="sortByViews"
              />
            </b-col>
            <b-col lg="4">
              <SortingButton
                text="По дате"
                @changed="sortByDate"
              />
            </b-col>
            <b-col lg="4">
              <VueSlider
                class="slider"
                v-model="slider"
                :min-range="10"
                :max="10000"
                :tooltip="'always'"
                :tooltip-formatter="toCurrency"
                :interval="0.1"
                :height="1"
                :dot-size="16"
                @drag-end="setSlider()"
              />
            </b-col>
          </b-row>
          
          <b-row>
            <b-col lg="3">
              <b-form-select
                v-model="filter.section_id"
                :options="sections"
                class="w-100"
                @change="$fetch"
              ></b-form-select>
            </b-col>
            <b-col lg="3">
              <b-form-select
                v-model="filter.type_id"
                :options="types"
                class="w-100"
                @change="$fetch"
              ></b-form-select>
            </b-col>
            <b-col lg="4">
              <b-form-select
                class="select-author"
                v-model="filter.user_id"
                :options="authorOptions"
                @change="$fetch"
              ></b-form-select>
            </b-col>
            <b-col lg="2">
              <b-button
                pill
                class="background-purple clear-filter"
                @click="reset"
              >
                Сбросить фильтр
              </b-button>
            </b-col>
          </b-row>

        </div>
      </b-collapse>

      <div class="pb-5">
        <b-row class="align-items-stretch">
          <b-col v-for="item in ads" :key="item.title" md="4" lg="4" class="mb-4">
            <Card :ad="item" />
          </b-col>
        </b-row>
      </div>

      <div
        v-if="!!searchText && !ads.length && !loading"
        class="text-muted mb-4 mt-4 d-flex justify-content-center"
      >
        Ничего не найдено, попробуйте изменить запрос
      </div>
      
      <div
        v-if="!searchText && !ads.length && !loading"
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
        ></b-pagination>
      </div>
    </b-container>
  </section>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import SortingButton from '@/components/SortingButton';
import Loader from '@/components/Loader';
import Card from '@/components/ads/card';
import VueSlider from 'vue-slider-component';
import { ModelParams, SortDirection } from '@/shared/constants';
import users from '@/mixins/users';
import utils from '@/mixins/utils';
import ads from '@/mixins/ads';
import adsFilter from '@/mixins/adsFilter';

export default {
  name: 'Ads',
  components: {
    Breadcrumbs,
    SortingButton,
    Card,
    VueSlider,
    Loader,
  },
  mixins: [utils, ads, users, adsFilter],
  async fetch() {
    this.loading = true;
    await Promise.all([
      this.getAds(this.getFetchParams, true),
      this.getUsers(),
      this.getSections(),
      this.getTypes(),
    ]);
    this.loading = false;
    this.breadcrumbs = [
      { label: 'Объявления', url: null },
    ];
  },
  data: () => ({
    ads: [],
    users: [],
    loading: false,
    breadcrumbs: [],
  }),
  head() {
    if (this.metaTags) {
      return {
        title: this.metaTags.title,
        meta: [
          {
            description: this.metaTags.description,
            keywords: this.metaTags.keywords,
          },
        ],
      };
    }
  },
};
</script>

<style scoped lang="scss">
  .slider {
    max-width: 90%;
    bottom: -22px;
    position: relative;
  }

  .filter-title {
    font-size: 13px;
    font-family: 'Roboto Thin', sans-serif;
    color: $gray;
    margin-bottom: 10px;
  }

  .ellipse-btn {
    border-radius: 15px;
  }
</style>
