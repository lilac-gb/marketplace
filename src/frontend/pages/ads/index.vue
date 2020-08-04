<template>
  <b-container>
    <Breadcrumbs></Breadcrumbs>
    <search-form @onSubmit="searchByName">
      <b-row>
        <b-col md="6">
          <b-form-select
            v-model="filter.category"
            :options="options.category"
            class="w-100"
          ></b-form-select>
        </b-col>
        <b-col md="6">
          <b-form-select
            v-model="filter.type_id"
            :options="options.type"
            class="w-100"
          ></b-form-select>
        </b-col>
      </b-row>
      <b-row class="mt-4">
        <b-col md="3" class="text-left">
          <p class="filter-title">Сортировка</p>
        </b-col>
        <b-col md="6" class="text-left">
          <p class="filter-title">Региональность</p>
        </b-col>
        <b-col md="3" class="text-left">
          <p class="filter-title">Стоимость</p>
        </b-col>
      </b-row>
      <b-row class="align-items-center">
        <b-col md="4" class="d-flex justify-content-between">
          <sorting-button text="По дате" />
          <sorting-button text="По просмотрам" />
        </b-col>
        <b-col md="5">
          <b-row>
            <b-col md="6">
              <b-form-select
                v-model="filter.country"
                :options="options.country"
                class="w-100"
              ></b-form-select>
            </b-col>
            <b-col md="6">
              <b-form-select
                v-model="filter.city"
                :options="options.city"
                class="w-100"
                :disabled="!filter.country"
              ></b-form-select>
            </b-col>
          </b-row>
        </b-col>
        <b-col md="3" class="pl-4 pr-4">
          <vue-slider
            v-model="slider.value"
            :min-range="20"
            :max="100"
            :tooltip="'always'"
            :tooltip-formatter="toCurrency"
            :interval="0.1"
            :height="1"
            :dot-size="16"
            @drag-end="searchByPrice"
          />
        </b-col>
      </b-row>
      <b-row class="pt-4 pb-4">
        <b-col md="6" class="d-flex align-items-center justify-content-start">
          <b-button
            v-for="item in options.sections"
            :key="item.name"
            class="background-purple ellipse-btn mr-3"
            @click="searchBySection(item.id)"
          >
            {{ item.name }}
          </b-button>
        </b-col>
        <b-col md="6" class="d-flex align-items-center justify-content-end">
          <b-button class="background-purple ellipse-btn" @click="resetFilter">
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
import _ from 'lodash';

const defaultFilter = Object.freeze({
  country: null,
  city: null,
  category: null,
  type_id: null,
  // priceFrom: 0,
  // priceTo: 100,
  section_id: null,
});

export default {
  name: 'Ads',
  components: {
    Breadcrumbs,
    'search-form': SearchForm,
    'sorting-button': SortingButton,
    'good-card': GoodCard,
    'vue-slider': VueSlider,
  },
  mixins: [utils],
  async fetch() {
    try {
      const response = await this.$axios.$get(`${config.api_url}/ad`, {
        params: this.getFetchParams(),
      });
      console.log(response);
      this.goods = [...response.data.models];
      this.pagination = { ...response.data._meta };

      const sections = await this.$axios.get(
        `${config.api_url}/ad/ads-sections`
      );
      this.options.sections = [...sections.data.data];
    } catch (e) {
      console.log(e);
    }
  },
  data: () => ({
    filter: { ...defaultFilter },
    options: {
      category: [{ value: null, text: 'Категории' }],
      type: [{ value: null, text: 'Тип' }],
      country: [{ value: null, text: 'Страна' }],
      city: [{ value: null, text: 'Город' }],
      sections: [],
    },
    slider: {
      value: [0, 100],
    },
    search: '',
    goods: [],
    pagination: {},
  }),
  mounted() {
    this.getFilterOptions();
  },
  methods: {
    async getFilterOptions() {
      const response = await this.$axios(`${config.api_url}/ad/ads-sections`);
      this.options.type = response.data.data.map((item) => ({
        value: item.value,
        text: item.name,
      }));
    },
    searchByName(searchLine) {
      this.search = searchLine.trim();
      this.$fetch();
    },
    searchByPrice() {
      const [priceFrom, priceTo] = this.slider.value;
      this.filter = {
        ...this.filter,
        priceFrom,
        priceTo,
      };
      this.$fetch();
    },
    resetFilter() {
      this.filter = { ...defaultFilter };
      this.slider.value = [0, 100];
    },
    getFetchParams() {
      const filter = Object.keys(this.filter).reduce((result, key) => {
        if (this.filter[key] || typeof this.filter[key] === 'number') {
          result[key] = this.filter[key];
        }
        return result;
      }, {});

      const params = {};

      if (this.search) {
        params.search = this.search;
      }

      if (!_.isEmpty(filter)) {
        params.filter = filter;
      }

      if (!_.isEmpty(params)) {
        return params;
      }
    },
    searchBySection(sectionId) {
      if (this.filter.section_id === sectionId) {
        this.filter.section_id = null;
      } else {
        this.filter.section_id = sectionId;
      }
      this.$fetch();
    },
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
</style>
