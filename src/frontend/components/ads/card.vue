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
        <b-btn @click="toCart(ad.price, ad.id)" class="mp-btn mp-btn-sm mp-btn-transparent d-flex">
          {{ ad.price }} $
        </b-btn>
      </div>
    </div>
  </div>
</template>

<script>
  import utils from '@/mixins/utils';
  import {mapActions} from 'vuex';

  export default {
    name: "adsCard",
    props: {
      ad: {type: Object, required: true}
    },
    mixins: [utils],
    data() {
      return {
        cart: [],
      }
    },
    mounted() {
      if (localStorage.cart) {
        this.cart = localStorage.cart;
      }
    },
    methods: {
      ...mapActions(['setMessage', 'setCart']),
      toCart(price, id) {
        let count = 0;
        if (localStorage.cart) {
          const existsGoods = JSON.parse(localStorage.cart);
          if (existsGoods) {

            let existItem = existsGoods.filter(item => (item.id === id));

            if (!!existItem.length) {
              existsGoods.forEach(item => {
                if (item.id === id) {
                  item.count += 1;
                  localStorage.cart = JSON.stringify(existsGoods);
                }
                count += item.count;
              })
            } else {
              existsGoods.push({id, price, count: 1});

              existsGoods.forEach(item => {
                count += item.count;
              });

              localStorage.cart = JSON.stringify(existsGoods);
            }
          }

          this.setCart(existsGoods);

        } else {
          localStorage.cart = JSON.stringify([{id, price, count: 1}]);
          this.setCart({id, price, count: 1});
          count = 1;
        }
        const cartCount = document.getElementById('cart-count');
        cartCount.innerText = count;
        this.setMessage(`Товар успешно добавлен в корзину`);
      }
    }
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
