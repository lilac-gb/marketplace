<template>
  <nav class="cabinet-nav">
    <b-list-group>
      <b-list-group-item
        exact
        active-class="active"
        to="/cabinet/about"
        class="cabinet-nav__item py-4"
      >
        <img src="assets/pics/icons/user_prpl.svg" alt="icon" />
        <span>О себе</span>
      </b-list-group-item>
      <b-list-group-item
        exact
        active-class="active"
        to="/cabinet/ads"
        class="cabinet-nav__item py-4"
      >
        <img src="assets/pics/icons/announcement_prpl.svg" alt="icon" />
        <span>Объявления</span>
      </b-list-group-item>
      <b-list-group-item
        exact
        active-class="active"
        to="/cabinet/publications"
        class="cabinet-nav__item py-4"
      >
        <img src="assets/pics/icons/feather_prpl.svg" alt="icon" />
        <span>Публикации</span>
      </b-list-group-item>
      <b-list-group-item
        exact
        active-class="active"
        to="/cabinet/companies"
        class="cabinet-nav__item py-4"
      >
        <img src="assets/pics/icons/factory 1_prpl.svg" alt="icon" />
        <span>Компания</span>
      </b-list-group-item>
    </b-list-group>
  </nav>
</template>

<script>
export default {
  name: 'CabinetNav',
};
</script>

<style lang="scss" scoped>
img {
  padding-right: 28px;
}
.cabinet-nav {
  &__item {
    border: none;
    &.active {
      color: #cb0e0e !important;
      background-color: #f8f9fa !important;
    }
    &:hover {
      color: #cb0e0e;
    }
  }
}
</style>
