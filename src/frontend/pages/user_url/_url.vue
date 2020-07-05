<template>
  <section>
    <b-container>
      <b-row style="min-height:48rem;" class="mt-5">
        <b-col>
          <h1>Link {{$route.params.url}}</h1>
          <h1>Link {{ link.id }}</h1>
        </b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
  export default {
    title: "${$route.params.url}",
    data: () => ({
      link: []
    }),
    async mounted() {
      //this.link = await this.$axios.$get(`https://api.marketplace.docker/page/${this.$route.params.url}?expand=_metaTags&_format=json`)
      let response = await this.$axios.$get(`https://jsonplaceholder.typicode.com/posts?_limit=1`)
      this.link = response[0]
      console.log(this.link)
    },
    // validate({params}) {
    //   return true //  /^\d+$/.test(params.id) например только цифры
    // }
  }
</script>

<style scoped>

</style>
