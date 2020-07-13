<template>
  <b-link
    href="#"
    @click.prevent="changeDirection"
    class="sort-button">
    <div>
      {{ text }}
      <font-awesome-icon :icon="['fas', 'chevron-down']" :class="downDisabled"/>
      <font-awesome-icon :icon="['fas', 'chevron-up']" :class="upDisabled"/>
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
      return this.direction === SortDirection.DESK ? 'text-light-gray' : '';
    },
    upDisabled() {
      return this.direction === SortDirection.ASK ? 'text-light-gray' : '';
    },
  },
  methods: {
    changeDirection() {
      this.direction = this.direction === SortDirection.ASK ? SortDirection.DESK : SortDirection.ASK;
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

  div {
    position: relative;
    width: fit-content;

    .fa-chevron-up {
      position: absolute;
      top: 1px;
      right: -12px;
    }
  }
}
</style>