<template>
  <header class="header">
    <b-navbar class="shadow-nav" toggleable="lg" type="light" variant="light" fixed="top">
      <b-container>
        <b-navbar-brand :to="`/`">
          <img src="~assets/logo/header-logo.svg" alt="header__logo"/>
        </b-navbar-brand>
        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav v-for="link in menu" :key="link.id">
            <b-nav-item
                exact
                :to="link.url" class="d-block p-2"
            >
              {{ link.label }}
            </b-nav-item>
          </b-navbar-nav>

          <b-navbar-nav class="ml-auto">
            <MiniCart/>
            <User v-if="isAuthenticated"/>
            <b-link v-else to="/login">Войти</b-link>
          </b-navbar-nav>
        </b-collapse>
      </b-container>
    </b-navbar>
  </header>
</template>

<script>
import config from '@/config/config';
import { mapGetters } from 'vuex';
import User from '@/components/user/User';
import MiniCart from '@/components/сart/MiniCart';

export default {
  name: 'Header',
  components: {
    User,
    MiniCart,
  },
  async fetch() {
    const header = await this.$axios.$get(`${config.api_url}/menu/header`, {
      headers: {
        Accept: 'application/json',
      },
    });
    this.menu = header.data.children;
  },
  data: () => ({
    menu: [],
  }),
  computed: mapGetters(['isAuthenticated']),
};
</script>

<style lang="scss" scoped>
.header {
  height: 50px;
  z-index: 50;
  .navbar {
    padding: 0 1rem;
  }
  .shadow-nav {
    box-shadow: 0 0 5px rgba(0, 0, 0, .6);
  }
  &__logo {
    height: 41px;
    width: 41px;

    & img {
      height: 100%;
      width: 100%;
    }
  }

  .navbar-light {
    .navbar-nav {
      .nav-link {
        color: $purple;
        &:hover, &.nuxt-link-active {
          color: $red;
        }
      }
    }
  }
}
</style>
