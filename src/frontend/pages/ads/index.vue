<template>
  <b-container>
    <Breadcrumbs></Breadcrumbs>
    <search-form>
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
            v-model="filter.type"
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
        <b-col md="3" class="d-flex justify-content-between">
          <sorting-button text="По дате" />
          <sorting-button text="По просмотрам" />
        </b-col>
        <b-col md="6">
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
          />
        </b-col>
      </b-row>
      <b-row class="pt-4 pb-4">
        <b-col md="6" class="d-flex align-items-center justify-content-start">
          <b-button
            v-for="item in options.sections"
            :key="item.name"
            class="background-purple ellipse-btn mr-3"
            @click="getBySection(item.id)"
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

const defaultFilter = Object.freeze({
  country: null,
  city: null,
  category: null,
  type: null,
  price: {
    from: 0,
    to: 100,
  },
  sections: [],
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
      const params = this.getFilterParams();

      const response = await this.$axios.$get(`${config.api_url}/ad`, {
        data: {
          options: params,
        },
      });
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
    goods: [],
    pagination: {},
  }),
  mounted() {
    this.getFilterOptions();
  },
  methods: {
    async getFilterOptions() {
      const response = await this.$axios(`${config.api_url}/ad/ads-sections`);
      const adsSections = response.data.data.map((item) => ({
        value: item.value,
        text: item.name,
      }));
      this.options.type.push(...adsSections);
    },
    getSliderRange() {
      const [from, to] = this.slider.value;
      return { from, to };
    },
    resetFilter() {
      this.filter = { ...defaultFilter };
      this.slider.value = [0, 100];
      console.log(this.filter);
    },
    getFilterParams() {
      const params = Object.keys(this.filter).reduce((result, key) => {
        if (this.filter[key]) {
          result[key] = this.filter[key];
        }
        return result;
      }, {});
      params.price = this.getSliderRange();
      return params;
    },
    getBySection(sectionId) {
      if (!this.filter.sections.includes(sectionId)) {
        this.filter.sections.push(sectionId);
      } else {
        this.filter.sections = this.filter.sections.filter(
          (s) => s !== sectionId
        );
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
