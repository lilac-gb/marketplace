<template>
  <section>
    <b-container>
      <b-row style="min-height: 48rem;" class="mt-5">
        <b-col>
          <!--          TODO: добавить хлебных крошек, см. ISS-21 -->
          <h1>{{ name }}</h1>

          <p v-html="description"></p>
        </b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
import config from '../config';

export default {
  async fetch() {
    const response = await this.$axios.$get(
      `${config.api_url}/page/${this.$route.params.url}?expand=_metaTags&_format=json`
    );
    const { name, description, _metaTags } = response.data;
    this.name = name;
    this.description = description;
    this.metaTags = _metaTags;
  },
  data: () => ({
    link: '',
    name: '',
    description: '',
    metaTags: {},
  }),
  head() {
    return {
      title: this.metaTags.title,
      meta: [
        {
          description: this.metaTags.description,
          keywords: this.metaTags.keywords,
        },
      ],
    };
  },
  // TODO: сделать валидацию вводимых роутов
  validate({ params }) {
    return /^\w+$/.test(params.url);
  },
};
</script>

<style scoped></style>
