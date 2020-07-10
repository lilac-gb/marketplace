<template>
  <div>
    <div class="wrapper">
      <Header />
      <nuxt />
    </div>

    <Footer />
  </div>
</template>

<script>
import Footer from '@/components/Footer';
import Header from '@/components/Header';

export default {
  components: { Footer, Header },
};
</script>

<style lang="scss" scoped>
.wrapper {
  min-height: 100vh;
  padding-bottom: $footer-height;
}
</style>
