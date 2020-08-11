import config from './config/config';

require('dotenv').config();
const { join } = require('path');
const { copySync, removeSync } = require('fs-extra');

export default {
  mode: 'spa',
  srcDir: __dirname,
  env: {
    api_url: process.env.API_URL || 'https://api.marketplace.docker',
    base_url: process.env.BASE_URL || 'https://marketplace.docker',
    appName: process.env.APP_NAME || 'Yii Nuxt',
  },

  /*
   ** Headers of the page
   */
  head: {
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || '',
      },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },
  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#fff' },
  /*
   ** Global CSS
   */
  css: [],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: ['~/plugins/vee-validate.js'],
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [],
  store: true,
  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    [
      'bootstrap-vue/nuxt',
      {
        icons: true,
      }
    ],
    '@nuxt/http',
    '@nuxtjs/style-resources',
    '@nuxtjs/auth',
    [
      'nuxt-fontawesome',
      {
        imports: [
          {
            set: '@fortawesome/free-brands-svg-icons',
            icons: ['fab'],
          },
          {
            set: '@fortawesome/free-solid-svg-icons',
            icons: ['fas'],
          },
        ],
      },
    ],
  ],
  styleResources: {
    scss: ['assets/scss/main.scss'],
  },
  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {},
  /*
   ** Build configuration
   */
  auth: {
    strategies: {
      local: {
        endpoints: {
          login: {
            url: `${config.api_url}/user/login`,
            method: 'post',
            propertyName: 'token',
          },
          user: {
            url: `${config.api_url}/user/me?expand=email`,
            method: 'get',
            propertyName: 'data',
          },
          logout: {
            url: `${config.base_host}/user/logout`,
          },
        },
      },
    },
    redirect: {
      home: '/cabinet',
    },
  },
  build: {
    extractCSS: true,
    transpile: ['vee-validate/dist/rules'],
    /*
     ** You can extend webpack config here
     */
    // extend(config, ctx) {},
  },
  hooks: {
    generate: {
      done(generator) {
        // Copy dist files to web/_nuxt
        if (
          generator.nuxt.options.dev === false &&
          generator.nuxt.options.mode === 'spa'
        ) {
          const publicDir = join(
            generator.nuxt.options.rootDir,
            'web',
            '_nuxt'
          );
          removeSync(publicDir);
          copySync(
            join(generator.nuxt.options.generate.dir, '_nuxt'),
            publicDir
          );
          copySync(
            join(generator.nuxt.options.generate.dir, '200.html'),
            join(publicDir, 'index.html')
          );
          removeSync(generator.nuxt.options.generate.dir);
        }
      },
    },
  },
};
