<template>
  <b-container id="publications" class="mt-4 mb-4 mp-container">
    <div>

    </div>
    <div></div>
    <div class="d-flex flex-row align-items-center">
      <b-link
        href="#"
        :disabled="currentPage === 1"
        @click.prevent="goLeft"
        class="mr-3 button-scroll text-purple">
        <font-awesome-icon :icon="['fas', 'chevron-left']"/>
      </b-link>
      <div class="publications-grid">
        <publication-card
          v-for="publication in publications"
          :key="publication.id"
          :publication="publication"/>
      </div>
      <b-link
        href="#"
        :disabled="currentPage === pageCount"
        @click.prevent="goRight"
        class="ml-3 button-scroll text-purple">
        <font-awesome-icon :icon="['fas', 'chevron-right']"/>
      </b-link>
    </div>
  </b-container>
</template>

<script>
import publications from '@/mixins/publications';
import { NewsModel, SortDirection } from '@/shared/constants';
import PublicationsCard from '@/components/publications/card';

export default {
  name: 'Publication',
  components: {
    'publication-card': PublicationsCard,
  },
  mixins: [
    publications
  ],
  async fetch() {
    await Promise.all([
      this.getPublication(this.$route.params.publication_id, true),
      this.getPublications(this.publicationsApiParams)
    ]);
  },
  data() {
    return {
      publications: [],
      publication: null,
      currentPage: 1,
      pageCount: 1,
      perPage: 3,
      totalCount: null,
      sortBy: NewsModel.CREATED_AT,
      sortDesc: SortDirection.ASK,
    };
  },
  computed: {
    publicationsApiParams() {
      return {
        expand: '_metaTags',
        page: this.currentPage,
        pageSize: this.perPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      };
    }
  },
  methods: {
    async goRight() {
      if (this.currentPage !== this.pageCount) {
        this.currentPage += 1;
        await this.getPublications(this.publicationsApiParams);
      }
    },
    async goLeft() {
      if (this.currentPage !== 1) {
        this.currentPage -= 1;
        await this.getPublications(this.publicationsApiParams);
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.publications-grid {
  display: grid;
  grid-template-columns: repeat(3, 380px);
  grid-template-rows: auto;
  grid-column-gap: 20px;
  grid-row-gap: 20px;
}

.button-scroll {
  font-size: 50px;
}

a.disabled {
  pointer-events: none;
  color: $gray;
}
</style>