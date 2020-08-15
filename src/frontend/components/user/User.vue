<template>
  <!-- Right aligned nav items -->
  <b-nav-item-dropdown right no-caret>
    <template v-slot:button-content>
      <div class="d-flex align-items-center justify-content-center">
        <img
            v-if="loggedInUser.images.preview"
            class="user-img"
            :src="loggedInUser.images.preview"
            alt="user-img"
        />
        {{ fullUserName }}
      </div>
    </template>
    <b-dropdown-item to="/cabinet/orders">
      <img src="~assets/pics/icons/cart-gray.svg" alt="icon"/>
      <span>Заказы</span>
    </b-dropdown-item>
    <b-dropdown-item to="/cabinet/about">
      <img src="~assets/pics/icons/user.svg" alt="icon"/>
      <span>Мои данные</span>
    </b-dropdown-item>
    <b-dropdown-item to="/cabinet/ads">
      <img src="~assets/pics/icons/announcement.svg" alt="icon"/>
      <span>Мои объявления</span>
    </b-dropdown-item>
    <b-dropdown-item to="/cabinet/publications">
      <img src="~assets/pics/icons/feather.svg" alt="icon"/>
      <span>Мои публикации</span>
    </b-dropdown-item>
    <b-dropdown-item to="/cabinet/companies">
      <img src="~assets/pics/icons/factory.svg" alt="icon"/>
      <span>Моя компания</span>
    </b-dropdown-item>
    <b-dropdown-item href="#" @click.prevent="logout">
      <img src="~assets/pics/icons/logout.svg" alt="icon"/>
      <span>Выход</span>
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: 'User',
    async fetch() {
      try {
        this.user = await this.$auth.user;
      } catch (e) {
        console.log(e);
      }
    },
    data: () => ({
      user: {},
    }),
    computed: {
      ...mapGetters(['loggedInUser']),
      fullUserName() {
        return `${this.user.first_name}${
          this.user.last_name ? ` ${this.user.last_name}` : ''
          }`;
      },
    },
    methods: {
      logout() {
        this.$auth.logout();
      },
    },
  };
</script>

<style scoped lang="scss">
  .user-img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
    border-radius: 50%;
  }

  .dropdown-menu {
    .dropdown-item {
      img {
        margin-right: 5px;
        width: 14px;
        height: 14px;
      }
    }
  }
</style>
