<template>
  <div class="nav-item d-flex align-items-center">
    <b-link class="pr-3" @click="processModal">
      <div class="position-relative">
        <div class="loader">
          <Loader v-show="loading"/>
        </div>
        <img src="~assets/pics/icons/cart.svg" alt="icon"/>
        <div id="cart-count" class="counter">0</div>
        <span>Корзина</span>
      </div>
    </b-link>
    <b-modal
        id="cartModal"
        v-model="openModal"
        size="xl"
        title="Форма заказа"
        @hidden="resetModal"
    >
      <b-table
          striped
          hover
          :items="cartResult"
          :fields="fields"
      >
        <template v-slot:table-caption>
          <h3 class="text-right w-100">
            <b>Количество:</b> {{cartResult.totalCount}} <b>на сумму:</b> {{cartResult.totalPrice}} $
          </h3>
        </template>
      </b-table>
      <template v-slot:modal-footer>
        <div class="w-100">
          <b-button
              variant="outline-danger"
              size="sm"
              @click="resetLocalStorage"
              class="float-left"
          >
            Очистить заказ
          </b-button>
          <b-button class="float-right mp-btn mp-btn-sm mp-btn-transparent">
            Оформить заказ
          </b-button>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import config from "@/config/config";
  import Loader from '@/components/Loader';

  export default {
    name: 'MiniCart',
    components: {Loader},
    data() {
      return {
        cart: [],
        cartResult: [],
        openModal: false,
        loading: false,
        fields: [
          {
            key: 'id',
            label: '#',
            sortable: true
          },
          {
            key: 'name',
            sortable: true,
            label: 'Название',
          },
          {
            key: 'count',
            label: 'Количество',
            sortable: true,
          },
          {
            key: 'price',
            label: 'Стоимость/шт $',
            sortable: true,
          },
          {
            key: 'total',
            label: 'Итого $',
            sortable: true,
          }
        ],
      }
    },
    mounted() {
      this.getLocalStorage()
    },
    methods: {
      resetLocalStorage() {
        localStorage.removeItem('cart');
        const cartCount = document.getElementById('cart-count');
        cartCount.innerText = 0;
        this.cart = [];
        this.cartResult = [];
        this.openModal = false;
      },
      async getLocalStorage() {
        if (localStorage.cart) {
          this.cart = JSON.parse(localStorage.cart);
          let count = 0;
          this.cart.forEach(item => count += item.count);
          const cartCount = document.getElementById('cart-count');
          cartCount.innerText = count;
        }
      },
      resetModal() {
        this.openModal = false;
        this.cart = [];
        this.cartResult = [];
        this.getLocalStorage();
      },
      processModal() {
        this.getLocalStorage();
        if (!!this.cart && !!this.cart.length) {
          let counter = 0;
          this.loading = true;
          let totalCounter = 0;
          let totalPrice = 0;
          this.cart.forEach((item, i) => {
            this.$http.$get(`${config.api_url}/ad/${item.id}`)
              .then((response) => {
                counter += 1;
                totalCounter += item.count;
                totalPrice += (item.count * item.price);
                this.cartResult[i] = {
                  id: item.id,
                  name: response.data.name,
                  url: response.data.url,
                  count: item.count,
                  price: item.price,
                  total: Math.floor((item.count * item.price) * 100) / 100,
                };
              }).then(() => {
              if (counter === this.cart.length) {
                this.cartResult['totalCount'] = totalCounter;
                this.cartResult['totalPrice'] = Math.floor(totalPrice * 100) / 100;
                this.loading = false;
                this.openModal = true;
              }
            });
          });
        } else {
          this.loading = false;
        }
      }
    },
  };
</script>

<style scoped lang="scss">
  .loader {
    .lds-dual-ring {
      display: inline-block;
      position: absolute;
      left: -6px;
      top: -5px;
      width: 15px;
      height: 15px;
      &:after {
        width: 30px;
        height: 30px;
        border: 2px solid $purple;
        border-color: $purple transparent $purple transparent;
        animation: lds-dual-ring 1.2s linear infinite;
      }
    }
  }

  @keyframes lds-dual-ring {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  .nav-item {
    .counter {
      background: $red;
      position: absolute;
      height: $counter-size;
      border-radius: 10px;
      padding: 0 4px;
      top: -5px;
      left: 9px;
      line-height: $counter-size;
      text-align: center;
      font-size: 10px;
      color: #fff;
    }

    & a {
      color: $purple;
    }

    & img {
      height: 17px;
      margin-right: 10px;
    }
  }
</style>
