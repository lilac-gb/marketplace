<template>
  <b-link
    href="#"
    @click.prevent="changeDirection"
    class="sort-button">
    <div class="d-flex flex-row">
      {{ text }}
      <div class="d-flex flex-row arrow-container">
        <font-awesome-icon :icon="['fas', 'chevron-down']" :class="downDisabled"/>
        <font-awesome-icon :icon="['fas', 'chevron-up']" :class="upDisabled"/>
      </div>
    </div>
  </b-link>
</template>

<script>
import { SortDirection } from '@/shared/constants';

export default {
  name: 'SortingButton',
  props: {
    text: { type: String, required: true },
  },
  data() {
    return {
      direction: SortDirection.ASK,
    };
  },
  computed: {
    downDisabled() {
      return this.direction === SortDirection.DESC ? 'text-light-gray' : '';
    },
    upDisabled() {
      return this.direction === SortDirection.ASK ? 'text-light-gray' : '';
    },
  },
  methods: {
    changeDirection() {
      this.direction = (this.direction === SortDirection.ASK ? SortDirection.DESC : SortDirection.ASK);
      this.$emit('changed', this.direction);
    },
  },
}
</script>

<style lang="scss" scoped>
.sort-button {
  font-weight: normal;
  font-size: 16px;
  line-height: 19px;
  color: $black;
  text-decoration: none;
  margin: 2px 5px 2px 5px;
  width: max-content;

  .arrow-container {
    position: relative;
    width: 23px;
    margin-left: 3px;

    .fa-chevron-up {
      position: absolute;
      top: 1px;
      right: -2px;
    }
  }
}
</style>
