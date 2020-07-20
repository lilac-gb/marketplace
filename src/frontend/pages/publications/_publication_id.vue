<template>
  <div>
    <b-container id="publication" class="mt-4 mb-4 mp-container">
      <div v-if="publication">
        <b-card
          style="max-width: none !important;"
          class="mb-4"
          :img-src="publication.coverImages.i1200x500"
          :img-alt="publication.name"
          img-top
          img-height="478"
          img-width="1180"/>
        <div class="mb-4 text-title">{{ publication.name }}</div>
        <div class="d-flex flex-row">
          <div class="card-details mr-4">
            <div v-if="!!publication.user" class="icon user">{{ publication.user.name }}</div>
            <div class="icon create-at">{{ timestampToDate(publication.created_at) }}</div>
            <div class="icon views">{{ publication.views }}</div>
            <div class="d-flex flex-row">
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'facebook-square']" />
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'twitter-square']" />
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'instagram-square']" />
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'whatsapp-square']" />
              </a>
            </div>
          </div>
          <div class="text-regular" v-html="publication.description"></div>
        </div>
      </div>
      <div></div>
    </b-container>
    <div class="mt-4 mb-4 d-flex flex-row justify-content-center">
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
            v-for="p in publications"
            :key="p.id"
            :publication="p"/>
        </div>
        <b-link
          href="#"
          :disabled="currentPage === pageCount"
          @click.prevent="goRight"
          class="ml-3 button-scroll text-purple">
          <font-awesome-icon :icon="['fas', 'chevron-right']"/>
        </b-link>
      </div>
    </div>
  </div>
</template>

<script>
import publications from '@/mixins/publications';
import utils from '@/mixins/utils';
import { NewsModel, SortDirection } from '@/shared/constants';
import PublicationsCard from '@/components/publications/card';

export default {
  name: 'Publication',
  components: {
    'publication-card': PublicationsCard,
  },
  mixins: [
    publications,
    utils
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

.social-link {
  display: block;
  font-size: 32px;
  margin-right: 34px;
  color: $gray;

  &:last-of-type {
    margin-right: 0;
  }
}
</style>