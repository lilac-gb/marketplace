<template>
  <b-card
    no-body
    class="filter-card w-100">
    <div class="header">Сортировка</div>
    <div :class="containerClass">
      <slot></slot>
    </div>
  </b-card>
</template>

<script>
export default {
  name: 'CardFilter',
  props: {
    containerClass: { type: String }
  }
}
</script>

<style lang="scss" scoped>
.filter-card {
  margin-bottom: 44px;
  max-width: none !important;
  height: auto !important;
  border: 0;

  .header {
    font-weight: 300;
    font-size: 13px;
    line-height: 15px;
    color: #605F5F;
  }
}
</style>