<template>
  <section id="main-page" class="mt-n2 min-vh-100">
    <div v-if="loading" class="main-loader">
      <Loader />
    </div>
    <div class="main-img">
      <b-container>
        <div class="main-search position-relative">
          <h1 class="main-shadow text-light text-center mb-3 mt-n5">
            Маркет<span class="text-purple main-light-shadow">Place</span>
            услуг, медицинского оборудования и специализированных препаратов
          </h1>
          <form class="searchForm position-relative" v-on:submit.prevent="submitSearch">
            <input
              class="form-control w-100"
              type="text"
              v-model="searchQuery"
              placeholder="Введите название необходимого товара или публикации"
              @keyup="submitSearch"
            >
            <span
              v-show="searchQuery"
              class="removeInput"
              @click="removeSearchQuery"
            >
              &times;
            </span>
          </form>
          <ul class="searchResult" v-show="isResult">
            <li
              v-for="elem in results"
              v-bind:key="elem.url"
            >
              <nuxt-link
                class="text-muted search-link"
                :to="elem.url"
              >
                {{ elem.name }}
              </nuxt-link>
            </li>
          </ul>
        </div>
      </b-container>
    </div>
    <b-container>
      <div class="d-flex justify-content-between align-items-center mt-5 mb-5 main-h2">
        <h2 class="text-uppercase">
          Популярные предложения
        </h2>
        <nuxt-link class="text-muted h3" :to="`/ads`">
          Смотреть все
        </nuxt-link>
      </div>
      <div class="container mt-3 mb-3">
        <b-row class="offer-grid">
          <AdCard
            v-for="ad in ads"
            :key="ad.id"
            :ad="ad"
          />
        </b-row>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-5 mb-5 main-h2">
        <h2 class="text-uppercase">
          Популярные публикации
        </h2>
        <nuxt-link class="text-muted h3" :to="`/publications`">
          Смотреть все
        </nuxt-link>
      </div>
      <div class="container mt-3 mb-5">
        <b-row class="publications-grid">
          <PublicationCard
            v-for="publication in publications"
            :key="publication.id"
            :publication="publication"
          />
        </b-row>
      </div>
    </b-container>
  </section>
</template>

<script>
  import AdCard from '@/components/ads/card';
  import PublicationCard from '@/components/publications/card';
  import Loader from '@/components/Loader';
  import config from '@/config';

  export default {
    name: 'Main',
    components: { Loader, AdCard, PublicationCard },
    data() {
      return {
        ads: [],
        inputValue: '',
        publications: [],
        results: [],
        isResult: false,
        loading: false,
        searchQuery: '',
      };
    },
    methods: {
      removeSearchQuery() {
        this.searchQuery = '';
        this.isResult = false;
      },
      async submitSearch() {
        let result = await this.$http.$post(`${config.api_url}/main-search`, {
          query: this.searchQuery,
        });
        this.results = result.data;
        this.isResult = !!result && !!result.data.length;
      },
      async getAds() {
        let result = await this.$http.$get(`${config.api_url}/ad/main-popular-ads`);
        this.ads = result.data;
      },
      async getPublications() {
        let result = await this.$http.$get(`${config.api_url}/news/main-popular-news`);
        this.publications = result.data;
      },
    },
    async fetch() {
      this.loading = true;
      await Promise.all([
        this.getAds(),
        this.getPublications(),
      ]);
      this.loading = false;
    },
    head() {
      return {
        title: 'Medical MarketPlace',
      };
    },
  };
</script>

<style lang="scss" scoped>
  #main-page {
    .main-shadow {
      text-shadow: 0 0 10px #000;
    }
    .main-light-shadow {
      text-shadow: 0 0 10px #FFF;
      font-weight: bold;
    }
    
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
      min-height: 60vh;
      .main-search {
        position: relative;
        z-index: 1;
        input {
          padding: 25px 20px;
        }
        .searchResult {
          background: #fff;
          padding: 20px;
          position: absolute;
          width: 100%;
          box-shadow: 0 10px 10px rgba(0, 0, 0, 0.5);
          list-style: none;
          li {
            margin-bottom: 10px;
          }
        }
        .removeInput {
          position: absolute;
          right: 8px;
          top: 16px;
          font-weight: 100;
          font-size: 45px;
          cursor: pointer;
        }
      }
      &:before {
        content: '';
        z-index: -1;
        top: 0;
        position: absolute;
        background-image: url('/images/bg.jpg');
        background-repeat: no-repeat;
        min-height: 60vh;
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
  
    @media screen and (max-width: 1200px) {
      .publications-grid {
        grid-template-columns: 1fr 1fr;
      }
      .offer-grid {
        grid-template-columns: 1fr 1fr;
      }
    }
  
    @media screen and (max-width: 900px) {
      .publications-grid {
        grid-template-columns: 1fr 1fr;
      }
      .offer-grid {
        grid-template-columns: 1fr 1fr;
      }
    }
  
    @media screen and (max-width: 800px) {
      .main-img{
        min-height: 100vh;
        &:before {
          min-height: 100vh;
          background-size: cover;
          background-position: left;
        }
      }
      .main-h2 {
        flex-direction: column;
        h2{
          font-size: 18px;
        }
        .h3 {
          margin-top: 10px;
          font-size: 14px;
        }
      }
      .main-search {
        h1 {
          font-size: 20px;
        }
      }
      .publications-grid {
        grid-template-columns: 1fr;
      }
      .offer-grid {
        grid-template-columns: 1fr;
      }
    }
  }
</style>
