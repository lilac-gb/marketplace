<template>
  <footer class="footer">
    <b-container>
      <b-row>
        <b-col md="9">
          <p class="footer-text">
            Curabitur aliquet quam id dui posuere blandit. Mauris blandit
            aliquet elit, eget tincidunt nibh pulvinar a. Nulla quis lorem ut
            libero malesuada feugiat. Vivamus magna justo, lacinia eget
            consectetur sed, convallis at tellus. Cras ultricies ligula sed
            magna dictum porta.
          </p>
          <nav class="footer-nav">
            <b-row>
              <b-col v-for="section in menu" :key="section.order" md="4">
                <h4>{{ section.label }}</h4>
                <ul>
                  <li v-for="link in section.items" :key="link.label">
                    <b-link class="d-block pt-2 pr-2 pb-2" :to="link.path">
                      {{ link.label }}
                    </b-link>
                  </li>
                </ul>
              </b-col>
            </b-row>
          </nav>
        </b-col>

        <b-col md="3">
          <div class="footer-right">
            <p class="footer-email">feedback@site.com</p>
            <p class="footer-tel">+343 943 98 945 966</p>
            <div class="footer-logo">
              <img src="~assets/logo/footer-logo.svg" alt="footer-logo"/>
            </div>
            <b-row class="d-flex justify-content-center">
              <a href="#" class="footer-social-link">
                <font-awesome-icon :icon="['fab', 'facebook-square']"/>
              </a>
              <a href="#" class="footer-social-link">
                <font-awesome-icon :icon="['fab', 'twitter-square']"/>
              </a>
              <a href="#" class="footer-social-link">
                <font-awesome-icon :icon="['fab', 'instagram-square']"/>
              </a>
            </b-row>
          </div>
        </b-col>
      </b-row>
      <div class="footer-bottom">
        <b-row class="p-1 justify-content-between">
          <span>&copy; SiteBrand, 2020</span>
          <span>Все права защищены</span>
        </b-row>
      </div>
    </b-container>
  </footer>
</template>

<script>
  import config from '@/config/config';

  export default {
    name: 'Footer',
    async fetch() {
      const footer = await this.$http.$get(`${config.api_url}/menu/footer`, {
        headers: {
          Accept: 'application/json',
        },
      });

      this.menu = footer.data.children;
    },
    data: () => ({
      menu: [],
    }),
  };
</script>

<style lang="scss" scoped>
  .footer {
    flex-shrink: 0;
    min-height: $footer-height;
    background-color: $purple;
    margin-top: -$footer-height;
    color: #ffffff;
    padding: 30px 0;
    font-family: 'Roboto Thin', sans-serif;
    
    &-right {
      text-align: center;
    }

    &-tel,
    &-email {
      font-size: 18px;
    }

    & a {
      color: #ffffff;
      font-size: 16px;
    }

    &-logo {
      width: 52px;
      height: 52px;
      margin: 20px auto;

      & img {
        height: 100%;
        width: 100%;
      }
    }

    &-text {
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

    &-bottom {
      margin-top: 40px;
    }
  }

  @media screen and (max-width: 786px) {
    h4 {
      margin-top: 30px;
      margin-bottom: 10px;
      text-decoration: underline;
      &:after{
        display: none !important;
      }
    }
    .footer-nav {
      text-align: center;
      margin-bottom: 30px;
    }
  }

  a.footer-social-link {
    display: block;
    font-size: 35px;
    margin-right: 1rem;

    &:last-of-type {
      margin-right: 0;
    }
  }
</style>
