<template>
  <header class="header">
    <b-container class="h-100">
      <b-row class="h-100 align-items-center">
        <b-col md="6" class="d-flex w-100 align-items-center">
          <div class="header__logo mr-2">
            <img src="~assets/logo/header-logo.svg" alt="header__logo" />
          </div>

          <nav class="header__nav">
            <ul class="d-flex">
              <li v-for="link in menu" :key="link.id">
                <b-link :to="link.path" class="d-block p-2"
                  >{{ link.label }}
                </b-link>
              </li>
            </ul>
          </nav>
        </b-col>
        <b-col md="6"></b-col>
      </b-row>
    </b-container>
  </header>
</template>

<script>
import config from '@/config/config';

export default {
  name: 'Header',
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
};
</script>

<style lang="scss" scoped>
.header {
  height: 50px;
  background: #ffffff;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  z-index: 50;

  &__logo {
    height: 41px;
    width: 41px;

    & img {
      height: 100%;
      width: 100%;
    }
  }

  a {
    color: $purple;
  }
}
</style>
