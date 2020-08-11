<template>
  <div class="mini-cart d-flex align-items-center">
    <b-link class="pr-3" v-b-modal.cartModal>
      <img src="~assets/pics/icons/cart.svg" alt="icon"/>
      <span>Корзина</span>
      <div id="cart-count" class="mini-cart__counter">0</div>
    </b-link>
    <cart-modal
        id="cartModal"
        :cart="cart"
        :showModal="showModal"
    />
  </div>
</template>

<script>
  import CartModal from '@/components/CartModal';

  export default {
    name: 'MiniCart',
    components: {
      'cart-modal': CartModal,
    },
    data() {
      return {
        cart: [],
        showModal: false,
      }
    },
    mounted() {
      if (localStorage.cart) {
        this.cart = JSON.parse(localStorage.cart);
        let count = 0;
        this.cart.forEach(item => count += item.count);
        const cartCount = document.getElementById('cart-count');
        cartCount.innerText = count;
      }
    },
  };
</script>

<style scoped lang="scss">
  .mini-cart {
    margin-right: 24px;
    position: relative;

    &__counter {
      background: $red;
      position: absolute;
      height: $counter-size;
      width: $counter-size;
      border-radius: 50%;
      top: 5px;
      left: 10px;
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
