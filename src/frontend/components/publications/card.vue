<template>
  <b-card
      :img-src="publication.coverImages.i600x250"
      :img-alt="publication.name"
      img-top
      img-height="156"
      img-width="380"
  >
    <div class="h-100 d-flex flex-column justify-content-center">
      <b-card-text>
        <router-link :to="{ name: 'publications-publication_id', params: { publication_id: publication.url }}">
          {{ publication.name }}
        </router-link>
      </b-card-text>
    </div>
    <div class="card-details">
      <div v-if="!!publication.user" class="icon user">{{ publication.user.name }}</div>
      <div class="icon create-at">{{ timestampToDate(publication.created_at) }}</div>
      <div class="icon views">{{ publication.views }}</div>
    </div>
  </b-card>
</template>

<script>
import utils from '@/mixins/utils';

export default {
  name: "PublicationsCard",
  props: {
    publication: { type: Object, required: true }
  },
  mixins: [utils],
}
</script>

<style lang="scss">
.card {
  max-width: 24rem !important;
  height: 428px;

  .card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    .card-text {
      font-weight: 300;
      font-size: 24px;
      line-height: 28px;
      margin-bottom: 34px;
    }

    .icon {
      display: flex;
      flex-direction: row;
      align-items: center;
      font-weight: 300;
      font-size: 18px;
      line-height: 21px;
      margin-bottom: 13px;

      &:last-of-type {
        margin-bottom: 0;
      }

      &.icon::before {
        content: '';
        display:block;
        background-repeat: no-repeat;
        margin-right: 14px;
      }

      &.user::before {
        height:24px;
        width:23px;
        background-size: 23px 24px;
        background-image: url('~assets/pics/icons/user.svg');
      }

      &.create-at::before {
        height:24px;
        width:24px;
        background-size: 24px 24px;
        background-image: url('~assets/pics/icons/clock.svg');
      }

      &.views::before {
        height:16px;
        width:25px;
        background-size: 25px 16px;
        background-image: url('~assets/pics/icons/eye.svg');
      }
    }
  }
}
</style>