<template>
  <div class="nav-item d-flex align-items-center">
    <b-link class="pr-3" @click="processModal">
      <div class="position-relative">
        <div class="loader">
          <Loader v-show="loading" />
        </div>
        <img src="~assets/pics/icons/cart.svg" alt="icon" />
        <div id="cart-count" class="counter">0</div>
        <span>Корзина</span>
      </div>
    </b-link>
    <b-modal
      id="cartModal"
      class="position-relative"
      v-model="openModal"
      size="xl"
      title="Форма оформления заказа"
      @hidden="resetModal"
    >
      <div v-if="loading" class="main-loader">
        <Loader />
      </div>
      <ValidationObserver v-slot="{ handleSubmit }">
        <b-form
          v-if="show"
          class="user-form d-flex flex-row flex-wrap"
          @submit.prevent="handleSubmit(onSubmit)"
        >
          <ValidationProvider
            v-slot="v"
            v-if="!isAuthenticated"
            rules="min:2|max:100|name|required"
            class="w-50"
          >
            <b-form-group
              id="input-group-1"
              label-for="input-1"
              description=""
              class="pr-3 pt-3"
            >
              <b-form-input
                id="input-1"
                v-model="name"
                placeholder="Введите ФИО"
                :class="{
                    'is-invalid': v.invalid && (v.touched || v.changed),
                    'is-valid': v.valid && v.dirty,
                  }"
              ></b-form-input>
              <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                {{ v.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          
          <ValidationProvider
            v-slot="v"
            v-if="!isAuthenticated"
            rules="min:5|max:100|email|required"
            class="w-50"
          >
            <b-form-group
              id="input-group-4"
              label-for="input-4"
              description=""
              class="pr-3 pt-3"
            >
              <b-form-input
                id="input-4"
                v-model="email"
                type="email"
                placeholder="Введите e-mail адрес"
                :class="{
                    'is-invalid': v.invalid && (v.touched || v.changed),
                    'is-valid': v.valid && v.dirty,
                  }"
              ></b-form-input>
              <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                {{ v.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          
          <ValidationProvider
            v-slot="v"
            rules="min:5|max:100|tel|required"
            class="w-50"
          >
            <b-form-group
              id="input-group-4"
              label-for="input-4"
              description=""
              class="pr-3 pt-3"
            >
              <b-form-input
                id="input-4"
                v-model="phone"
                type="tel"
                placeholder="Введите телефон"
                :class="{
                    'is-invalid': v.invalid && (v.touched || v.changed),
                    'is-valid': v.valid && v.dirty,
                  }"
              ></b-form-input>
              <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                {{ v.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          
          <ValidationProvider
            v-slot="v"
            rules="min:5|max:200"
            class="w-50"
          >
            <b-form-group
              id="input-group-4"
              label-for="input-4"
              description=""
              class="pr-3 pt-3"
            >
              <b-form-input
                id="input-4"
                v-model="address"
                type="text"
                placeholder="Введите адрес доставки если она необходима"
                :class="{
                     'is-invalid': v.invalid && (v.touched || v.changed),
                     'is-valid': v.valid && v.dirty,
                   }"
              ></b-form-input>
              <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                {{ v.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          
          <ValidationProvider
            v-slot="v"
            rules="min:5|max:1000"
            class="w-100"
          >
            <b-form-group
              id="input-group-1"
              label-for="input-1"
              description="Примечания для мененджера по желанию"
              class="pr-3 pt-3"
            >
              <b-form-textarea
                id="input-1"
                v-model="text"
                placeholder="Введите сообщение"
                :class="{
                     'is-invalid': v.invalid && (v.touched || v.changed),
                     'is-valid': v.valid && v.dirty,
                   }"
              ></b-form-textarea>
              <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                {{ v.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          
          <b-table
            striped
            v-if="!loading"
            hover
            :items="cartResult"
            :fields="fields"
          >
            <template v-slot:cell(count)="row">
              <div class="d-flex align-items-center justify-content-center">
                <b-button size="sm" @click="countUp(row.item.id)" variant="link" class="text-muted">
                +
              </b-button>
                <div>{{ row.item.count }}</div>
              <b-button size="sm" @click="countDown(row.item.id)" variant="link" class="text-muted">
                -
              </b-button>
              </div>
            </template>
            <template v-slot:cell(actions)="row">
              <b-button size="sm" @click="deletePosition(row.item.id)" variant="link" class="text-muted">
                <b-icon class="text-muted" icon="trash-fill"></b-icon>
              </b-button>
            </template>
            <template v-slot:table-caption>
              <h3 class="text-right w-100">
                {{ totalCount }}
                {{ declinationName(totalCount, ['позиция', 'позиции', 'позиций']) }}
                на сумму: {{ totalPrice }} $
              </h3>
            </template>
          </b-table>
          
          <div class="d-flex align-items-center justify-content-between w-100">
            <b-button
              variant="outline-danger"
              size="sm"
              @click="resetLocalStorage"
              class="float-left"
            >
              Очистить заказ
            </b-button>
            <b-button type="submit" class="float-right mp-btn mp-btn-sm mp-btn-transparent">
              {{ loading ? 'Подождите...' : 'Оформить заказ' }}
            </b-button>
          </div>
        </b-form>
      </ValidationObserver>
      
      <template v-slot:modal-footer>
        {{' '}}
      </template>
    </b-modal>
  </div>
</template>

<script>
  import config from '@/config/config';
  import Loader from '@/components/Loader';
  import orders from '@/mixins/orders';
  import { ValidationObserver, ValidationProvider } from 'vee-validate';
  import { mapGetters, mapActions } from 'vuex';
  import utils from '@/mixins/utils';
  
  export default {
    name: 'MiniCart',
    components: { Loader, ValidationObserver, ValidationProvider },
    mixins: [orders, utils],
    data() {
      return {
        email: '',
        name: '',
        phone: '',
        address: '',
        text: '',
        cart: [],
        cartResult: [],
        user: {},
        show: true,
        breadcrumbs: [],
        openModal: false,
        loading: false,
        totalCount: 0,
        totalPrice: 0,
        fields: [
          {
            key: 'id',
            label: '#',
            sortable: true,
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
          },
          {
            key: 'actions',
            label: 'Действия',
          },
        ],
      };
    },
    mounted() {
      this.getLocalStorage();
    },
    computed: {
      ...mapGetters(['loggedInUser', 'isAuthenticated']),
    },
    methods: {
      ...mapActions(['setMessage']),
      countUp(id) {
        let bufferCard = [];
        if (localStorage.cart) {
          this.cart = JSON.parse(localStorage.cart);
          this.cart.forEach((item) => {
            if (item.id === id) {
              item.count += 1;
              localStorage.cart = JSON.stringify(this.cart);
            }
          });
          this.cartResult.forEach((item) => {
            if (item.id === id) {
              item.count += 1;
              bufferCard = this.cartResult;
            }
          });
          this.cartResult = bufferCard;
          this.processModal();
        }
      },
      countDown(id) {
        let bufferCard = [];
        if (localStorage.cart) {
          this.cart = JSON.parse(localStorage.cart);
          this.cart.forEach((item) => {
            if (item.id === id && item.count !== 1) {
              item.count -= 1;
              localStorage.cart = JSON.stringify(this.cart);
            }
          });
          this.cartResult.forEach((item) => {
            if (item.id === id && item.count !== 1) {
              item.count -= 1;
              bufferCard = this.cartResult;
            }
          });
          this.cartResult = bufferCard;
          this.processModal();
        }
      },
      deletePosition(id) {
        if (localStorage.cart) {
          this.cart = JSON.parse(localStorage.cart);
          this.cart = this.cart.filter(item => item.id !== id);
          this.cartResult = this.cartResult.filter(item => item.id !== id);
          if (this.cartResult.length === 0) {
            this.loading = false;
            this.openModal = false;
          }
          localStorage.cart = JSON.stringify(this.cart);
          this.processModal();
        }
      },
      async onSubmit() {
        this.loading = true;
        let payload = {
          email: this.email || this.user.email,
          name: this.name || `${this.user.first_name}${this.user.last_name ? ` ${this.user.last_name}` : ''}`,
          address: this.address,
          phone: this.phone,
          items: this.cartResult,
          text: this.text,
        };
        let result = await this.createOrder(payload);
        if (result.success) {
          if (this.isAuthenticated) {
            this.resetLocalStorage();
            this.setMessage('Ваш заказ успешно размещен в кабинете');
            this.$router.push({ name: 'cabinet-orders' });
          } else {
            this.resetLocalStorage();
            this.setMessage(
              'Заказ успешно сформирован! В случае, если Вы еще не зарегистрированы, то для Вас будет создан кабинет, где вы сможете отслеживать заказ. Письмо с инструкциями будет отправлено на указанный email');
            this.$router.push('/');
          }
        } else {
          this.setMessage('Что-то пошло не так...');
        }
        this.loading = false;
      },
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
        this.user = { ...this.$auth.user };
        if (!!this.cart && !!this.cart.length) {
          let counter = 0;
          this.loading = true;
          let totalCounter = 0;
          let totalPrice = 0;
          this.cart.forEach((item, i) => {
            this.$http.$get(`${config.api_url}/ad/${item.id}`).then((response) => {
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
                this.totalCount = totalCounter;
                this.totalPrice = Math.floor(totalPrice * 100) / 100;
                this.loading = false;
                this.openModal = true;
              }
            });
          });
        } else {
          this.loading = false;
        }
      },
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

  .modal-footer {
    padding: 0;
    border-top: 0;
  }
</style>
