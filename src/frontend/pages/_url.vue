<template>
  <section>
    <b-container>
      <b-row class="mt-5">
        <b-col>
          <h1>{{ name }}</h1>

          <p v-html="description"></p>
        </b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
import config from '@/config';
import { constructUrl } from '@/shared/api';

export default {
  async fetch() {
    let params = { expand: '_metaTags' };
    let response = await this.$axios.$get(
      constructUrl(`${config.api_url}/page/${this.$route.params.url}`, params)
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
  async validate({ params }) {
    let response = await fetch(`${config.api_url}/page/${params.url}`);
    return response.ok;
  },
};
</script>

<style scoped></style>
