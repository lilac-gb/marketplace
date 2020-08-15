import config from '@/config';
import { constructUrl } from '@/shared/api';

export default {
  methods: {
    async getOrders(params) {
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/order`, params),
      );

      this.orders = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;
    },
    async getMyOrders(params) {
      console.log(params);
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/order/my`, params),
      );
      this.$http.setToken(false);
      this.orders = result.data.models;
      this.currentPage = result.data._meta.currentPage;
      this.pageCount = result.data._meta.pageCount;
      this.perPage = result.data._meta.perPage;
      this.totalCount = result.data._meta.totalCount;
    },
    async getOrder(id) {
      let params = {};
      let result = await this.$http.$get(
        constructUrl(`${config.api_url}/order/${id}`, params),
      );
      this.order = result.data;
    },
    async deleteOrder(id) {
      this.$http.setToken(this.$auth.getToken('local'));
      await this.$http.$delete(`${config.api_url}/order/${id}/delete`);
      this.$http.setToken(false);
    },
    async createOrder(payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$post(`${config.api_url}/order`, payload);
      this.$http.setToken(false);
      return result;
    },
    async updateOrder(id, payload) {
      this.$http.setToken(this.$auth.getToken('local'));
      let result = await this.$http.$put(
        `${config.api_url}/order/${id}/update`,
        payload,
      );
      this.$http.setToken(false);
      return result;
    },
  },
};
