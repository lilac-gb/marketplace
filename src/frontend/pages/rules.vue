<template>
  <section>
    <b-container>
      <b-row style="min-height:48rem;" class="mt-5">
        <b-col>
          <h2>{{meta.data.success}}</h2>

          <p>{{meta.data.description}}</p>

          {{meta}}
        </b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
  export default {
    async asyncData ({$axios}) {
      let meta = await $axios.$get(`https://api.marketplace.docker/page/rules?expand=_metaTags&_format=json`)
      return {meta}
    },
    data: () =>({
      meta: []
    }),
    head() {
      return {
        title: this.meta.data.name,
        meta: [
          { redicolossimo: 'bla-bla-bla #2' } //локальные динамические метатеги
        ],
      };
    },

  }
</script>

<style scoped>

</style>
