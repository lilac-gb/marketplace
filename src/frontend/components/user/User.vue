<template>
  <div class="user d-flex align-items-center position-relative">
    <div class="user__photo">
      <img src="~assets/pics/user/placeholder.png" alt="user_img" />
    </div>
    <a
      href="#"
      class="user__btn"
      :class="{ active: dropdown }"
      @click.prevent="dropdown = !dropdown"
    >
      <div class="user__btn-text">
        {{ user.first_name + ' ' + user.last_name }}
      </div>
    </a>
    <div v-show="dropdown" class="user__dropdown">
      <ul class="user__dropdown-menu">
        <li>
          <b-link class="d-flex align-items-center pt-1 pb-2">
            <img src="~assets/pics/icons/user.svg" alt="icon" />
            <span>Мои данные</span>
          </b-link>
        </li>
        <li>
          <b-link class="d-flex align-items-center pt-1 pb-2">
            <img src="~assets/pics/icons/announcement.svg" alt="icon" />
            <span>Мои объявления</span>
          </b-link>
        </li>
        <li>
          <b-link class="d-flex align-items-center pt-1 pb-2">
            <img src="~assets/pics/icons/pen.svg" alt="icon" />
            <span>Мои публикации</span>
          </b-link>
        </li>
        <li>
          <b-link
            class="d-flex align-items-center pt-1 pb-2"
            @click.prevent="logout"
          >
            <img src="~assets/pics/icons/logout.svg" alt="icon" />
            <span>Выход</span>
          </b-link>
        </li>
      </ul>
    </div>
  </div>
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
    dropdown: false,
    user: {},
  }),
  methods: {
    logout() {
      this.$auth.logout();
    },
  },
};
</script>

<style scoped lang="scss">
.user {
  position: relative;
  width: 217px;

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
    width: 160px;
    display: flex;
    justify-content: space-between;

    &-text {
      width: 140px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    &:after {
      content: url('~assets/pics/icons/arrow.svg');
      justify-self: flex-end;
      width: 16px;
      height: 15px;
      transition: transform 0.3s;
    }

    &.active:after {
      transform: rotate(180deg);
    }
  }

  &__dropdown {
    position: absolute;
    left: 0;
    right: 0;
    top: 40px;
    background: #fff;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    z-index: 90;
    padding: 18px;

    & img {
      height: 17px;
      margin-right: 15px;
    }
  }
}
</style>
