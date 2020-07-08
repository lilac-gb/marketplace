<template>
  <section>
    <b-container>
      <b-row style="min-height:48rem;" class="mt-5">
        <b-col>
<!--          TODO: добавить хлебных крошек-->
<!--          TODO: допилить верстку страницы "текст и название страницы"-->
          <h1>Link {{$route.params.url}}</h1>
          <h1>Link {{ link }}</h1>
        </b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
  export default {
    data: () => ({
      link: ''
    }),
    async mounted() {
      this.link = await this.$axios.$get(`https://api.marketplace.docker/page/${this.$route.params.url}?expand=_metaTags&_format=json`)
    },
    head() {
      //const {title, description, keywords} = this.link.meta;
      return {
        title: `Rules`,
        // TODO: настроить разборку метатегов
        // meta: [
        //   {
        //     description: description,
        //     keywords: keywords
        //   } //локальные динамические метатеги
        // ],
      };
    },
    // TODO: сделать валидацию вводимых роутов
    // validate({params}) {
    //   return true //  /^\d+$/.test(params.id) например только цифры
    // }
  }
</script>

<style scoped>

</style>
