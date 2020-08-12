import config from '@/config/config';
import _ from 'lodash';
import { NewsModel, SortDirection } from '@/shared/constants';

export default {
  data: () => ({
    options: {
      types: {
        default: { value: null, text: 'Тип' },
        values: [],
      },
      country: {
        default: { value: null, text: 'Страна' },
        values: [],
      },
      city: {
        default: { value: null, text: 'Город' },
        values: [],
      },
      category: {
        default: { value: null, text: 'Категории' },
        values: [],
      },
      sections: {
        default: { name: 'Все', id: null },
        values: [],
      },
      categories: {
        default: { text: 'Категории', value: null },
        values: [],
      },
    },
    search: '',
    filter: {},
    slider: [0, 10000],
    sortBy: NewsModel.CREATED_AT,
    sortDesc: SortDirection.ASK,
  }),
  computed: {
    selectedSection() {
      return this.filter.section_id;
    },
  },
  methods: {
    computedOptions(optionName) {
      const option = this.options[optionName];
      if (option) {
        return option.default
          ? [option.default, ...option.values]
          : [...option.values];
      }
    },
    async getOptions() {
      const sections = await this.$axios.$get(
        `${config.api_url}/ad/ads-sections`
      );
      const types = await this.$axios.$get(`${config.api_url}/ad/ads-types`);

      this.options.sections.values = sections.data;
      this.options.types.values = types.data.map((option) => ({
        value: option.id,
        text: option.name,
      }));
    },
    getFetchParams() {
      const filter = Object.keys(this.filter).reduce((result, key) => {
        if (this.filter[key] || typeof this.filter[key] === 'number') {
          result[key] = this.filter[key];
        }
        return result;
      }, {});

      const params = {};
      params.page = this.currentPage;
      params.pageSize = this.perPage;
      params.sortBy = this.sortBy;
      params.sortDesc = this.sortDesc;

      if (this.search) {
        params.search = this.search;
      }

      if (!_.isEmpty(filter)) {
        params.filter = filter;
      }
      return params;
    },
    reset() {
      this.filter = {
        country: null,
        city: null,
        category: null,
        type_id: null,
        priceFrom: 0,
        priceTo: 10000,
        section_id: null,
      };
      this.sortBy = NewsModel.CREATED_AT;
      this.sortDesc = SortDirection.ASK;
      this.slider = [0, 10000];
      this.$fetch();
    },
    setSlider() {
      const [priceFrom, priceTo] = this.slider;
      this.filter = { ...this.filter, priceFrom, priceTo };
      this.$fetch();
    },
    searchByName(searchLine) {
      this.search = searchLine.trim();
      this.$fetch();
    },
    searchBySection(sectionId) {
      if (this.filter.section_id === sectionId) {
        return;
      }
      this.filter.section_id = sectionId;
      this.$fetch();
    },
    sortByDate(direction) {
      this.sortBy = NewsModel.VIEWS;
      this.sortDesc = direction;
      this.$fetch();
    },
    sortByViews(direction) {
      this.sortBy = NewsModel.VIEWS;
      this.sortDesc = direction;
      this.$fetch();
    },
  },
  mounted() {
    this.reset();
  },
};
