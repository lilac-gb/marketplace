<template>
  <b-card
    :img-src="previewSrc"
    img-alt="good-img"
    img-height="160"
    img-top
    class="good-card h-100"
  >
    <div class="d-flex flex-column justify-content-between h-100">
      <div class="good-card__body">
        <b-card-text class="good-card__name">
          <b-link :to="`/ads/${card.id}`" class="text-dark">
            {{ card.name }}
          </b-link>
        </b-card-text>
        <div class="good-card__info">
          <div class="good-card__text-location good-card__text">
            <font-awesome-icon
              :icon="['fas', 'adjust']"
              class="good-card__info-icon"
            />
            {{ card.sectionName.name }}
          </div>
          <div class="good-card__text-views good-card__text">
            <font-awesome-icon
              :icon="['fas', 'eye']"
              class="good-card__info-icon"
            />
            {{ card.views }}
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="good-card__add-text">Добавить в корзину</p>
        <b-button class="good-card__add-btn pl-3 pr-3">
          {{ card.price }} &euro;
        </b-button>
      </div>
    </div>
  </b-card>
</template>

<script>
import utils from '@/mixins/utils';

export default {
  name: 'GoodCard',
  mixins: [utils],
  props: {
    card: {
      type: Object,
      required: true,
    },
  },
  computed: {
    previewSrc() {
      return (
        this.$props.card.preview ||
        'https://balluff-ua.com/wp-content/themes/balluff/img/noImg.jpg'
      );
    },
  },
};
</script>

<style lang="scss" scoped>
.good-card {
  font-size: 18px;
  font-family: 'Roboto Thin', sans-serif;
  color: $black;

  & img {
    object-fit: cover;
    width: 100%;
  }

  & .card-body {
    flex-direction: column;
    justify-content: space-between;
  }

  &__name {
    min-height: 3em;
    display: flex;
    align-items: center;
  }

  &__info {
    &-icon {
      color: $light-gray;
      margin-right: 10px;
      width: 25px;
    }
  }

  &__text {
    padding: 5px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;

    &-author,
    &-location {
      text-decoration: underline;
    }
  }

  &__add-text {
    font-size: 18px;
    color: $dark-gray;
    font-family: 'Roboto Thin', sans-serif;
    margin: 0;
  }

  &__add-btn {
    border: 1px solid $purple;
    color: $purple;
    background-color: white;
    font-family: 'Roboto Regular', sans-serif;

    &:hover {
      color: white;
      background-color: $purple;
    }
  }
}
</style>
