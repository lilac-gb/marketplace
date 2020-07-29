<template>
  <!-- Right aligned nav items -->
  <b-nav-item-dropdown right no-caret>
    <template v-slot:button-content>
      <div class="d-flex align-items-center justify-content-center">
        <img class="user-img" :src="user.images && user.images.preview" alt="user-img"/>
        {{ fullUserName }}
      </div>
    </template>
    <b-dropdown-item href="#">
      <img src="~assets/pics/icons/user.svg" alt="icon"/>
      <span>Мои данные</span>
    </b-dropdown-item>
    <b-dropdown-item href="#">
      <img src="~assets/pics/icons/announcement.svg" alt="icon"/>
      <span>Мои объявления</span>
    </b-dropdown-item>
    <b-dropdown-item href="#">
      <img src="~assets/pics/icons/feather.svg" alt="icon"/>
      <span>Мои публикации</span>
    </b-dropdown-item>
    <b-dropdown-item href="#">
      <img src="~assets/pics/icons/factory.svg" alt="icon"/>
      <span>Моя компания</span>
    </b-dropdown-item>
    <b-dropdown-item href="#" @click.prevent="logout">
      <img src="~assets/pics/icons/logout.svg" alt="icon"/>
      <span>Выход</span>
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>

<script>
  export default {
    name: 'User',
    async fetch() {
      try {
        this.user = await this.$auth.user;
      } catch (e) {
        console.log(e);
      }
    },
    data: () => ({
      user: {},
    }),
    methods: {
      logout() {
        this.$auth.logout();
      },
    },
    computed: {
      fullUserName() {
        return `${this.user.first_name}${this.user.last_name ? ` ${this.user.last_name}` : ''}`;
      },
    },
  };
</script>

<style scoped lang="scss">
  .user-img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
    border-radius: 50%;
  }

  .dropdown-menu {
    .dropdown-item {
      img {
        margin-right: 5px;
        width: 14px;
        height: 14px;
      }
    }
  }

  /*.user {
    position: relative;

    & a {
      color: $purple;
    }

    &__photo {
      height: $user-photo-size;
      width: $user-photo-size;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 1rem;

      & img {
        height: 100%;
        width: 100%;
      }
    }

    &__btn {
      display: flex;
      justify-content: space-between;

      &-text {
        max-width: 160px;
        margin-right: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      &:after {
        width: 10px;
        height: 10px;
        content: '';
        position: relative;
        top: 4px;
        transform: rotate(45deg);
        border-style: solid;
        border-color: rgb(78, 41, 132);
        border-image: initial;
        border-width: 0 2px 2px 0;
      }

      &.active:after {
        top: 7px;
        transform: rotate(225deg);
      }
    }

    &__dropdown {
      position: absolute;
      min-width: 210px;
      right: 0;
      top: 40px;
      background: #fff;
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
      z-index: 90;
      padding: 18px;

      & img {
        height: 17px;
        margin-right: 15px;
      }
    }
  }*/
</style>
