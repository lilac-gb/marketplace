<template>
  <b-container class="mt-4" id="publications">
    <b-card-group columns>
      <b-card
        v-for="publication in publications"
        :key="publication.id"

        title="Card Title"
        img-src="~/assets/publications/card_pic.svg"
        img-alt="Image"
        img-top
        tag="article"
        style="max-width: 20rem;"
        class="mb-2">
        <b-card-text>
          Some quick example text to build on the card title and make up the bulk of the card's content.
        </b-card-text>

        <b-button href="#" variant="primary">Go somewhere</b-button>
      </b-card>
    </b-card-group>
  </b-container>
</template>

<script>
import { appDomen } from '@/shared/constants';

export default {
  name: "publications",
  data() {
    return {
      publications: []
    }
  },
  async fetch() {
   let result = await this.$http.$get(`${appDomen}/news?expand=_metaTags`);
   this.publications = result.data.models;
  }
}
</script>

<style lang="scss">
  #publications {
    .card {
    }
  }
</style>