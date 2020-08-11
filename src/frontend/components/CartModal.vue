<template>
  <b-modal id="cartModal" size="xl" title="Форма заказа">
    <b-table
        striped
        hover
        :items="cartResult"
        :fields="fields"
    ></b-table>
    <template v-slot:modal-footer>
      <div class="w-100">
        <b-button
            variant="outline-danger"
            size="sm"
            class="float-left"
        >
          Очистить заказ
        </b-button>
        <b-button
            variant="primary"
            size="sm"
            class="float-right"
        >
          Оформить заказ
        </b-button>
      </div>
    </template>
  </b-modal>
</template>

<script>
  import config from '@/config/config';
  import {mapActions} from 'vuex';

  export default {
    name: 'CartModal',
    props: {
      cart: {type: Array, required: true},
      showModal: {type: Boolean, required: true}
    },
    data() {
      return {
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
            label: 'Стоимость/шт',
            sortable: true,
          },
          {
            key: 'total',
            label: 'Итого',
            sortable: true,
          }
        ],
        cartResult: [],
      }
    },
    async fetch() {
      await this.apiAds();
    },
    methods: {
      apiAds() {
        this.cartResult = [];
        if (this.cart) {
          this.cart.forEach((item, i) => {
            this.$http.$get(`${config.api_url}/ad/${item.id}`)
              .then(response => {
                this.cartResult[i] = {
                  id: item.id,
                  name: response.data.name,
                  url: response.data.url,
                  count: item.count,
                  price: item.price,
                  total: (item.count * item.price),
                }
              }).catch((error) => {
              if (error.response && error.response.data) {
                console.log(error.response)
              }
            });
          })
        }
      }
    },
  };
</script>

<style lang="scss" scoped>
  .footer {
    min-height: $footer-height;
    background-color: $purple;
    margin-top: -$footer-height;
    color: #ffffff;
    padding: 30px 0;
    font-family: 'Roboto Thin', sans-serif;

    &__right {
      text-align: center;
    }

    &__tel,
    &__email {
      font-size: 18px;
    }

    & a {
      color: #ffffff;
      font-size: 16px;
    }

    &__logo {
      width: 85px;
      height: 85px;
      margin: 20px auto;

      & img {
        height: 100%;
        width: 100%;
      }
    }

    &__text {
      margin-bottom: 42px;
      font-size: 18px;
    }

    & h4 {
      font-size: 18px;

      &:after {
        content: '';
        display: block;
        width: 80px;
        height: 1px;
        background: #fff;
        margin: 1rem 0 5px 0;
      }
    }

    &__bottom {
      margin-top: 40px;
    }
  }

  a.footer__social-link {
    display: block;
    font-size: 35px;
    margin-right: 1rem;

    &:last-of-type {
      margin-right: 0;
    }
  }
</style>
