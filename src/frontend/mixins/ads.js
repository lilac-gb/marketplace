import config from '@/config';
import { constructUrl } from '@/shared/api';

export default {
  data: () => ({
    types: [],
    sections: [],
    metaTags: null,
  }),
  methods: {
    async getAds(params, metatags = false) {
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/ad`, params)
      );

      this.ads = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async getMyAds(params, metatags = false) {
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/ad/my`, params)
      );
      this.$http.setToken(false);
      this.ads = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async getAd(id, metatags = false, gallery = false) {
      let params = {};
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      if (gallery) {
        params['expand'] = 'gallery';
      }

      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/ad/${id}`, params)
      );
      this.ad = result.data;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async publishAd(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$post(`${config.api_url}/ad/${id}/publish`);
      this.$http.setToken(false);
    },
    async deleteAd(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$delete(`${config.api_url}/ad/${id}/delete`);
      this.$http.setToken(false);
    },
    async createAd(payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$post(`${config.api_url}/ad`, payload);
      this.$http.setToken(false);
      return result;
    },
    async updateAd(id, payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$put(
        `${config.api_url}/ad/${id}/update`,
        payload
      );
      this.$http.setToken(false);
      return result;
    },
    async getSections() {
      const sections = await this.$axios.$get(
        `${config.api_url}/ad/ads-sections`
      );

      let result = [{ text: 'Направление', value: null }];

      sections.data.map((option) =>
        result.push({
          value: option.id,
          text: option.name,
        })
      );

      this.sections = result;
    },
    async getTypes() {
      const types = await this.$axios.$get(`${config.api_url}/ad/ads-types`);

      let result = [{ text: 'Тип', value: null }];

      types.data.map((option) =>
        result.push({
          value: option.id,
          text: option.name,
        })
      );

      this.types = result;
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
