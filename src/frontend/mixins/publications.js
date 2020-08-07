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
      }

      let result = await this.$http.$get(constructUrl(`${config.api_url}/news/${id}`, params));
      this.publication = result.data;
      if (metatags) {
        this.metaTags = result.data._metaTags;
      }
    },
    async publishPublication(id) {
      await this.$http.$post(`${config.api_url}/news/${id}/publish`);
    },
    async deletePublication(id) {
      await this.$http.$post(`${config.api_url}/news/${id}/delete`);
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