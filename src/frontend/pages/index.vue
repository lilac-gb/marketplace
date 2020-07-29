<template>
  <div class="mt-n1 main-page">
    <div class="main-img">
      <b-container class="main-search">
        <b-input-group>
          <b-form-input
              v-model="searchText"
              class="mp-input search-field mr-2"
              placeholder="Введите название продукта или компании"
          />
          <b-button
              class="background-purple mp-button-purple mr-2"
              @click="$fetch"
          >
            ПОИСК
          </b-button>
        </b-input-group>
      </b-container>
    </div>
    <b-container>
      <div class="d-flex justify-content-between align-items-center mt-5 mb-5">
        <h2 class="text-uppercase">
          Популярные предложения
        </h2>
        <nuxt-link class="text-muted h3" :to="`/ads`">
          Смотреть все
        </nuxt-link>
      </div>
      <div class="container mt-3 mb-3">
        <b-row class="offer-grid">
          <ad-card
              v-for="p in ads"
              :key="p.id"
              :ad="p"
          />
        </b-row>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-5 mb-5">
        <h2 class="text-uppercase">
          Популярные публикации
        </h2>
        <nuxt-link class="text-muted h3" :to="`/publications`">
          Смотреть все
        </nuxt-link>
      </div>
      <div class="container mt-3 mb-3">
        <b-row class="publications-grid">
          <publication-card
              v-for="publication in publications"
              :key="publication.id"
              :publication="publication"
          />
        </b-row>
      </div>
    </b-container>
  </div>
</template>

<script>
  import AdCard from '@/components/ads/card';
  import PublicationCard from '@/components/publications/card';
  import config from "@/config";

export default {
  name: 'Main',
  components: {
    'ad-card': AdCard,
    'publication-card': PublicationCard,
  },
  data() {
    return {
      ads: [],
      publications: [],
    }
  },
  methods: {
    async getAds() {
      let result = await this.$http.$get(`${config.api_url}/ad/main-popular-ads`);
      this.ads = result.data;
    },
    async getPublications() {
      let result = await this.$http.$get(`${config.api_url}/news/main-popular-news`);
      this.publications = result.data;
    }
  },
  async fetch() {
    this.getAds();
    this.getPublications();
  },
  head() {
    return {
      title: 'Medical MarketPlace',
    };
  },
};
</script>

<style lang="scss" scoped>
  .main-page {
    .publications-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
      grid-template-rows: auto;
      grid-column-gap: 20px;
      grid-row-gap: 20px;
    }

    .offer-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      grid-template-rows: auto;
      grid-column-gap: 20px;
      grid-row-gap: 20px;
    }

    .main-page {
      min-height: 100vh;
    }
    .main-img {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 50vh;
      .main-search {
        position: relative;
        z-index: 1;
      }
      &:before {
        content: '';
        z-index: -1;
        top: 0;
        position: absolute;
        background-image: url('/images/bg.jpg');
        background-repeat: no-repeat;
        min-height: 50vh;
        width: 100%;
        background-size: 100%;
        margin-bottom: 45px;
      }
      &:after {
        position: absolute;
        content: '';
        display: block;
        z-index: 0;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.47);
      }
    }

    @media screen and (max-width: 786px) {
      .publications-grid {
        display: grid;
        grid-template-columns: 1fr;
      }
      .offer-grid {
        display: grid;
        grid-template-columns: 1fr;
      }
    }

    @media screen and (max-width: 900px) {
      .publications-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }
      .offer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }
    }

    @media screen and (max-width: 1200px) {
      .publications-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
      }
      .offer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
      }
    }
  }
</style>
