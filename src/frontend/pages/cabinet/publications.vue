<template>
  <b-container class="pt-3 pb-3">
    <Breadcrumbs />
    <b-row>
      <b-col cols="4">
        <CabinetNav />
      </b-col>
      <b-col>
        <div class="p-3 border bg-light">Тута публикации</div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import publications from '@/mixins/publications';
import users from '@/mixins/users';
import { NewsModel, SortDirection } from '@/shared/constants';

export default {
  name: 'Publications',
  components: { CabinetNav, Breadcrumbs },
  mixins: [publications, users],
  middleware: ['auth'],
  async fetch() {
    await Promise.all([
      this.getPublications(this.publicationsApiParams, true),
      this.getUsers(),
    ]);
  },
  data() {
    return {
      publications: [],
      users: [],
      searchText: null,
      authorFilterValue: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 12,
      totalCount: null,
      sortBy: NewsModel.CREATED_AT,
      sortDesc: SortDirection.ASK,
    };
  },
  computed: {
    publicationsApiParams() {
      let params = {
        page: this.currentPage,
        pageSize: this.perPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      };
      if (this.searchText) {
        params['News[name]'] = this.searchText;
      }
      if (this.authorFilterValue) {
        params['user_id'] = this.authorFilterValue;
      }
      return params;
    }
  }
};
</script>
