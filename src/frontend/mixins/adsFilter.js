import config from '@/config/config';
import _ from 'lodash';
import { ModelParams, SortDirection } from '@/shared/constants';
import { getFullName } from '@/shared/utils';

export default {
  data: () => ({
    currentPage: 1,
    perPage: 12,
    totalCount: null,
    searchText: '',
    filter: {},
    slider: [0, 10000],
    sortBy: ModelParams.CREATED_AT,
    sortDesc: SortDirection.DESC,
  }),
  computed: {
    authorOptions() {

      let result = [{ text: 'Все', value: null }];

      this.users.map((user) => result.push({
        value: user.id,
        text: getFullName(user),
      }));

      return result;
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

      if (this.searchText) {
        params.search = this.searchText;
      }

      console.log(filter);

      if (!_.isEmpty(filter)) {
        params.filter = Object.keys(filter).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(filter[key])).join('&');
      }
      return params;
    },
  },
  methods: {
    reset() {
      this.searchText = '';
      this.filter = {
        type_id: null,
        priceFrom: 0,
        priceTo: 10000,
        section_id: null,
        user_id: null,
      };
      this.sortBy = ModelParams.CREATED_AT;
      this.sortDesc = SortDirection.ASK;
      this.slider = [0, 10000];
      this.$fetch();
    },
    fetchMoreItems() {
      this.perPage += 12;
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
      this.sortBy = ModelParams.VIEWS;
      this.sortDesc = direction;
      this.$fetch();
    },
    sortByViews(direction) {
      this.sortBy = ModelParams.VIEWS;
      this.sortDesc = direction;
      this.$fetch();
    },
  },
  mounted() {
    this.reset();
  },
};
