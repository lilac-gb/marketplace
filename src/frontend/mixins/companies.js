import config from '@/config';
import { constructUrl } from '@/shared/api';

export default {
  data: () => ({
    metaTags: null,
  }),
  methods: {
    async getCompanies(params, metatags = false) {
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/company`, params)
      );
      this.companies = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async getMyCompanies(params, metatags = false) {
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/company/my`, params)
      );
      this.$http.setToken(false);
      this.companies = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async getCompany(id, metatags = false) {
      let params = {};
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/company/${id}`, params),
      );
      this.company = result.data;
      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async publishCompany(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$post(`${config.api_url}/company/${id}/publish`);
      this.$http.setToken(false);
    },
    async deleteCompany(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$delete(`${config.api_url}/company/${id}/delete`);
      this.$http.setToken(false);
    },
    async createCompany(payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$post(`${config.api_url}/company`, payload);
      this.$http.setToken(false);
      return result;
    },
    async updateCompany(id, payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$put(
        `${config.api_url}/company/${id}/update`,
        payload,
      );
      this.$http.setToken(false);
      return result;
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
