<template>
  <div>
    <b-container id="ad" class="mb-4">
      <div v-if="ad">
        <div
            class="w-100 item-img"
            v-if="ad.coverImages"
            :style="{ backgroundImage: `url(${ad.coverImages.i1200x500})` }"
        />
        <div class="mb-5 text-title">
          {{ ad.name }}
        </div>
        <div class="d-flex flex-row">
          <div class="card-details mr-4">
            <div class="mb-4 text-center">
              <b-btn
                  @click="toCart(ad.price, ad.id)"
                  class="mp-btn mp-btn-sm text-center mp-btn-transparent mb-2 w-100"
              >
                {{ ad.price }} $
              </b-btn>
              <div class="text-muted text-center">
                Добавить в корзину
              </div>
            </div>
            <div v-if="!!ad.user" class="icon user">{{ ad.user.name }}</div>
            <div class="icon create-at">{{ timestampToDate(ad.created_at) }}</div>
            <div class="icon views">{{ ad.views }}</div>
            <div class="d-flex flex-row mt-5">
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'facebook-square']"/>
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'twitter-square']"/>
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'instagram-square']"/>
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'whatsapp-square']"/>
              </a>
            </div>
          </div>
          <div class="text-regular" v-html="ad.description"></div>
        </div>
      </div>
    </b-container>
  </div>
</template>

<script>
  import utils from '@/mixins/utils';
  import ads from '@/mixins/ads';
  import {mapActions} from 'vuex';

  export default {
    name: 'Ad',
    mixins: [utils, ads],
    async fetch() {
      await this.getAd(this.$route.params.ad_id, true);
    },
    data() {
      return {
        ad: null,
        cart: [],
        currentPage: 1,
        pageCount: 1,
        perPage: 4,
        totalCount: null,
      };
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

<style lang="scss" scoped>
  .item-img {
    background-repeat: no-repeat;
    height: 400px;
    background-size: cover;
    margin-bottom: 45px;
  }

  .ads-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
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

  .big-image-card {
    max-width: none !important;
  }
</style>