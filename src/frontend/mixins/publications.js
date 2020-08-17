import config from '@/config';
import { constructUrl } from '@/shared/api';

export default {
  data: () => ({
    metaTags: null,
  }),
  methods: {
    async getPublications(params, metatags = false) {
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/news`, params)
      );
      this.publications = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async getMyPublications(params, metatags = false) {
      if (metatags) {
        params['expand'] = '_metaTags';
      }

      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/news/my`, params)
      );
      this.$http.setToken(false);
      this.publications = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;

      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async getPublication(id, metatags = false) {
      let params = {};
      if (metatags) {
        params['expand'] = '_metaTags';
        params['expand'] = 'gallery';
      }

      let result = await this.$http.$get(constructUrl(`${config.api_url}/news/${id}`, params));
      this.publication = result.data;
      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async publishPublication(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$post(`${config.api_url}/news/${id}/publish`);
      this.$http.setToken(false);
    },
    async deletePublication(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$delete(`${config.api_url}/news/${id}/delete`);
      this.$http.setToken(false);
    },
    async createPublication(payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$post(`${config.api_url}/news`, payload);
      this.$http.setToken(false);
      return result;
    },
    async updatePublication(id, payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$put(`${config.api_url}/news/${id}/update`, payload);
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