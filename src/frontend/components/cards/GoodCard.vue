<template>
  <b-card
    :img-src="previewSrc"
    img-alt="good-img"
    img-height="160"
    img-top
    class="good-card h-100"
  >
    <b-card-text class="good-card__name">
      {{ card.name }}
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
    <div class="d-flex justify-content-between align-items-center">
      <p class="add-to-cart-text">Добавить в корзину</p>
      <b-button class="background-purple pl-3 pr-3">
        {{ toCurrency(card.price) }}
      </b-button>
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
        'https://miro.medium.com/max/1200/1*mk1-6aYaf_Bes1E3Imhc0A.jpeg'
      );
    },
  },
};
</script>

<style lang="scss" scoped>
.add-to-cart-text {
  font-size: 18px;
  color: $dark-gray;
  font-family: 'Roboto Thin', sans-serif;
}

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
}
</style>
