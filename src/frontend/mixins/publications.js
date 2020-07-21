import config from '@/config';
import { constructUrl } from '@/shared/api';

export default {
  methods: {
    async getPublications(params) {
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/news`, params)
      );
      this.publications = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;
    },
    async getPublication(id) {
      let result = await this.$http.$get(`${config.api_url}/news/${id}`);
      this.publication = result.data;
    }
  }
};