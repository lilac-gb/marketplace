<template>
  <div class="mt-2">
    <vue-confirm-dialog />
    <div class="wrapper">
      <b-alert
        :show="!!message && dismissCountDown"
        dismissible
        class="custom-alert position-fixed fixed-top mt-3 ml-5 mr-5"
        fade
        @dismissed="clearMessage"
        @dismiss-count-down="countDownChanged(5)"
      >
        <div class="message">{{ message }}</div>
      </b-alert>
      <Header />
      <nuxt />
    </div>

    <Footer />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Footer from '@/components/Footer';
import Header from '@/components/Header';
import VueConfirmDialog from 'vue-confirm-dialog';
import Vue from 'vue'

Vue.use(VueConfirmDialog);
Vue.component('vue-confirm-dialog', VueConfirmDialog.default);

export default {
  components: { Footer, Header },
  computed: mapGetters(['message']),
  data: () => ({
    dismissCountDown: 5,
  }),
  methods: {
    ...mapActions(['clearMessage']),
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
  }
};
</script>

<style lang="scss" scoped>
.wrapper {
  min-height: 100vh;
  padding-bottom: $footer-height;
}

.custom-alert {
  z-index: 1100;
  width: max-content;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
  background: $purple;
  color: white;
  border-color: $purple;
  max-width: 50%;
  display: flex;
  align-items: center;
}
@media screen and (max-width: 786px) {
  .custom-alert{
    max-width: 100%;
  }
}
.alert-dismissible {
  .close {
    position: absolute;
    margin-top: 0;
    right: 14px;
    color: inherit;
    font-weight: 100;
  }
}
</style>
