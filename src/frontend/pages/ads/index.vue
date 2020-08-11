<template>
  <b-container class="pb-5">
    <Breadcrumbs class="mt-3"></Breadcrumbs>
    <search-form @onSubmit="searchByName">
      <b-row>
        <b-col md="6">
          <b-form-select
            v-model="filter.category"
            :options="computedOptions('categories')"
            class="w-100"
          ></b-form-select>
        </b-col>
        <b-col md="6">
          <b-form-select
            v-model="filter.type_id"
            :options="computedOptions('types')"
            class="w-100"
            @change="$fetch()"
          ></b-form-select>
        </b-col>
      </b-row>
      <b-row class="mt-4">
        <b-col md="4" class="text-left">
          <p class="filter-title">Сортировка</p>
        </b-col>
        <b-col md="5" class="text-left">
          <p class="filter-title">Региональность</p>
        </b-col>
        <b-col md="3" class="text-left">
          <p class="filter-title">Стоимость</p>
        </b-col>
      </b-row>
      <b-row class="align-items-center">
        <b-col md="4" class="d-flex justify-content-between">
          <sorting-button text="По дате" @changed="sortByDate" />
          <sorting-button text="По просмотрам" @changed="sortByViews" />
        </b-col>
        <b-col md="5">
          <b-row>
            <b-col md="6">
              <b-form-select
                v-model="filter.country"
                :options="computedOptions('country')"
                class="w-100"
              ></b-form-select>
            </b-col>
            <b-col md="6">
              <b-form-select
                v-model="filter.city"
                :options="computedOptions('city')"
                class="w-100"
                :disabled="!filter.country"
              ></b-form-select>
            </b-col>
          </b-row>
        </b-col>
        <b-col md="3" class="pl-4 pr-4">
          <vue-slider
            v-model="slider"
            :min-range="20"
            :max="100"
            :tooltip="'always'"
            :tooltip-formatter="toCurrency"
            :interval="0.1"
            :height="1"
            :dot-size="16"
            @drag-end="setSlider()"
          />
        </b-col>
      </b-row>
      <b-row class="pt-4 pb-4">
        <b-col md="6" class="d-flex align-items-center justify-content-start">
          <b-button
            v-for="item in computedOptions('sections')"
            :key="item.name"
            class="background-purple ellipse-btn btn-sm mr-3"
            :disabled="item.id === selectedSection"
            @click="searchBySection(item.id)"
          >
            {{ item.name }}
          </b-button>
        </b-col>
        <b-col md="6" class="d-flex align-items-center justify-content-end">
          <b-button
            class="background-purple ellipse-btn btn-sm"
            @click="reset()"
          >
            Сбросить фильтр
          </b-button>
        </b-col>
      </b-row>
    </search-form>
    <section class="pt-5 pb-5">
      <b-row class="align-items-stretch">
        <b-col v-for="item in goods" :key="item.title" md="4" lg="4">
          <good-card :card="item" />
        </b-col>
      </b-row>
    </section>

    <b-row class="justify-content-center">
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
    </b-row>
  </b-container>
</template>

<script>
import config from '@/config/config';
import Breadcrumbs from '@/components/Breadcrumbs';
import SearchForm from '@/components/forms/SearchForm';
import SortingButton from '@/components/SortingButton';
import GoodCard from '@/components/cards/GoodCard';
import VueSlider from 'vue-slider-component';
import utils from '@/mixins/utils';
import adsFilter from '@/mixins/adsFilter';

export default {
  name: 'Ads',
  components: {
    Breadcrumbs,
    'search-form': SearchForm,
    'sorting-button': SortingButton,
    'good-card': GoodCard,
    'vue-slider': VueSlider,
  },
  mixins: [utils, adsFilter],
  async fetch() {
    try {
      await Promise.all([this.getAds(), this.getOptions()]);
    } catch (e) {
      console.log(e);
    }
  },
  data: () => ({
    search: '',
    goods: [],
    currentPage: 1,
    perPage: 12,
    totalCount: null,
    metaTags: null,
  }),
  methods: {
    async getAds() {
      try {
        const response = await this.$axios.$get(`${config.api_url}/ad`, {
          params: this.getFetchParams(),
        });
        console.log(response);
        this.goods = [...response.data.models];
        this.totalCount = response.data._meta.totalCount;
        this.perPage = response.data._meta.perPage;
        this.currentPage = response.data._meta.currentPage;
        this.metaTags = response.data._metaTags;
      } catch (e) {
        console.log(e);
      }
    },
  },
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
