<template>
  <div class="card d-flex flex-column justify-content-between">
    <div
        class="w-100 item-img"
        :style="{ backgroundImage: `url(${ad.preview})` }"
    />
    <div class="params d-flex align-items-center">
      <div class="params-item mr-2">
        {{ ad.type }}
      </div>
      <div class="params-item">
        {{ ad.section }}
      </div>
    </div>

    <b-card-text class="px-4">
      <router-link class="text-dark" :to="ad.url">
        {{ ad.name }}
      </router-link>
    </b-card-text>
    <div class="px-4 pb-4 text-muted">
      <div v-if="!!ad.author" class="icon user">
        {{ ad.author }}
      </div>
      <div class="icon create-at">
        {{ timestampToDate(ad.created_at) }}
      </div>
      <div class="icon views">
        {{ ad.views }}
      </div>
      <div class="mt-3 d-flex align-items-center justify-content-between">
        <div class="text-muted">
          Добавить в корзину
        </div>
        <nuxt-link :to="`#`" class="mp-btn mp-btn-sm mp-btn-transparent d-flex">
          {{ ad.price }} $
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
  import utils from '@/mixins/utils';

  export default {
    name: "adsCard",
    props: {
      ad: {type: Object, required: true}
    },
    mixins: [utils],
  }
</script>

<style lang="scss">
  .item-img {
    background-repeat: no-repeat;
    height: 140px;
    background-size: cover;
    margin-bottom: 15px;
  }

  .params {
    position: relative;
    top: -28px;
    left: 22px;
    font-size: 14px;
    .params-item {
      background: $purple;
      color: #fff;
      padding: 0px 10px;
      border-radius: 10px;
    }
  }
</style>
