<template>
  <div class="card d-flex flex-column justify-content-between">
    <div
        class="w-100 item-img"
        :style="{ backgroundImage: `url(${publication.coverImages.i600x250})` }"
    />
    <b-card-text class="px-4">
      <router-link class="text-dark" :to="{
          name: 'publications-publication_id',
          params: { publication_id: publication.url }}"
      >
        {{ publication.name }}
      </router-link>
    </b-card-text>
    <div class="px-4 pb-4 text-muted">
      <div v-if="!!publication.user" class="icon user">
        {{ publication.user.name }}
      </div>
      <div class="icon create-at">
        {{ timestampToDate(publication.created_at) }}
      </div>
      <div class="icon views">
        {{ publication.views }}
      </div>
    </div>
  </div>
</template>

<script>
  import utils from '@/mixins/utils';

  export default {
    name: "PublicationsCard",
    props: {
      publication: {type: Object, required: true}
    },
    mixins: [utils],
  }
</script>

<style lang="scss">
  .item-img {
    background-repeat: no-repeat;
    height: 140px;
    background-size: cover;
    margin-bottom: 15px;
  }
</style>