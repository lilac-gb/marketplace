<template>
  <div class="breadcrumbs-component-wrapper">
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
      <b-breadcrumb-item v-for="item in crumbs" :key="item.key" :to="item.to">
        <span itemProp="name">{{ item.text }}</span>
        <meta itemProp="position" :content="`${item.key}`" />
      </b-breadcrumb-item>
    </b-breadcrumb>
  </div>
</template>
<script>
export default {
  computed: {
    crumbs: function () {
      let pathArray = this.$route.path.split('/');
      pathArray.shift();
      let breadcrumbs = pathArray.reduce((breadcrumbArray, path, idx) => {
        const text = this.$route.matched[idx].meta.breadCrumb || path;
        breadcrumbArray.push({
          path: path,
          to: breadcrumbArray[idx - 1]
            ? '/' + breadcrumbArray[idx - 1].path + '/' + path
            : '/' + path,
          text: text.charAt(0).toUpperCase() + text.slice(1),
          key: ++idx + 1,
        });
        return breadcrumbArray;
      }, []);
      return breadcrumbs;
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
