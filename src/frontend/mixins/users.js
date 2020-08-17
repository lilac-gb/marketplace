import config from '@/config';

export default {
  methods: {
    async getUsers() {
      let result = await this.$http.$get(`${config.api_url}/user`);
      this.users = result.data.models;
    },
  },
};