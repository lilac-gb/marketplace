<template>
  <section id="page">
    <b-container class="mt-4 mb-4">
      <Breadcrumbs :items="breadcrumbs" class="d-flex" />
      <b-row class="flex-column">
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
import Breadcrumbs from '@/components/Breadcrumbs';

export default {
  components: { Breadcrumbs },
  async fetch() {
    let params = { expand: '_metaTags' };
    let response = await this.$axios.$get(
      constructUrl(`${config.api_url}/page/${this.$route.params.url}`, params)
    );
    const { name, description, _metaTags } = response.data;
    this.name = name;
    this.description = description;
    this.metaTags = _metaTags;
    this.breadcrumbs = [
      { label: this.name, url: null }
    ];
  },
  data: () => ({
    link: '',
    name: '',
    description: '',
    metaTags: {},
    breadcrumbs: [],
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
