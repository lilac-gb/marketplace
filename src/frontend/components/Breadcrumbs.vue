<template>
  <b-row class="breadcrumbs-component-wrapper">
    <b-breadcrumb
      class="breadcrumbs-holder"
      item-scope
      item-type="http://schema.org/BreadcrumbList"
    >
      <b-breadcrumb-item
        to="/"
        item-prop="itemListElement"
        item-scope
        item-type="http://schema.org/ListItem"
      >
        <meta itemprop="position" content="1" />
        <span itemProp="name">Главная</span>
      </b-breadcrumb-item>
      <b-breadcrumb-item v-for="(item, key) in items" :key="key" :to="item.url" v-if="item.url">
        <span itemProp="name">{{ item.label }}</span>
        <meta itemProp="position" :content="`${key + 2}`" />
      </b-breadcrumb-item>
      <b-breadcrumb-item v-for="item in items" :key="item.key" active v-if="!item.url">
        {{ item.label}}
      </b-breadcrumb-item>
    </b-breadcrumb>
  </b-row>
</template>
<script>
export default {
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style lang="scss" scoped>
.breadcrumb {
  ::before {
    color: $gray;
  }
  background-color: transparent;
  a {
    color: $gray;
    text-decoration: none;
    :hover {
      color: $dark-gray;
    }
  }
}
</style>
