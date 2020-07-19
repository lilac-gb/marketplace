<template>
  <div>
    <div class="wrapper">
      <b-alert
        :show="!!message"
        dismissible
        class="custom-alert position-fixed fixed-top mt-3 ml-5 mr-5"
        fade
        @dismissed="clearMessage"
      >
        {{ message }}
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

export default {
  components: { Footer, Header },
  computed: mapGetters(['message']),
  methods: mapActions(['clearMessage']),
};
</script>

<style lang="scss" scoped>
.wrapper {
  min-height: 100vh;
  padding-bottom: $footer-height;
}

.custom-alert {
  z-index: 100;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
  background: $purple;
  color: white;
  border-color: $purple;
}
</style>
