<template>
  <section id="cabinet-user-edit" class="min-vh-100">
    <div v-if="loading" class="main-loader">
      <Loader />
    </div>
    <b-container class="py-3 vh-100">
    <Breadcrumbs :items="breadcrumbs" />
    <b-row>
      <b-col lg="2" md="2" sm="3" xs="4">
        <CabinetNav />
      </b-col>
      <b-col lg="7" md="7" sm="6" xs="12">
        <ValidationObserver v-slot="{ handleSubmit }">
          <b-form
            v-if="show"
            class="user-form d-flex flex-row flex-wrap"
            @submit.prevent="handleSubmit(onSubmit)"
            @reset="onReset"
          >
            <div class="d-flex flex-row flex-wrap border-bottom">
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:100|name"
                class="w-50"
              >
                <b-form-group
                  id="input-group-1"
                  label-for="input-1"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-1"
                    v-model="user.first_name"
                    placeholder="Введите имя"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  ></b-form-input>
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:100|name"
                class="w-50"
              >
                <b-form-group
                  id="input-group-2"
                  label-for="input-2"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-2"
                    v-model="user.last_name"
                    placeholder="Введите фамилию"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  ></b-form-input>
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:100|username"
                class="w-50"
              >
                <b-form-group
                  id="input-group-3"
                  label-for="input-3"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-3"
                    v-model="user.username"
                    placeholder="Введите имя пользователя"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  ></b-form-input>
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <div class="w-50 pr-0 py-3 user-form-text">
                Имя пользователя должно содержать только латинские буквы, оно
                будет использоваться для просмотра вашей карточки по адресу
                /@nickname
              </div>
            </div>
            <div class="d-flex flex-row flex-wrap border-bottom">
              <ValidationProvider
                v-slot="v"
                rules="min:5|max:100|email"
                class="w-50"
              >
                <b-form-group
                  id="input-group-4"
                  label-for="input-4"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-4"
                    v-model="user.email"
                    type="email"
                    placeholder="Введите e-mail адрес"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  ></b-form-input>
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <div class="w-50 pr-0 py-3 user-form-text">
                Если вы решите изменить свой e-mail, мы вышлем на новый адрес
                письмо для подтверждения.
              </div>
            </div>
            <div class="d-flex flex-row w-100 justify-content-between">
              <div class="w-50">
                <ValidationProvider v-slot="v" class="w-50">
                  <b-form-group
                    id="input-group-5"
                    label-for="input-5"
                    description=""
                    class="w-100 pr-3 pt-3"
                  >
                    <b-form-input
                      id="input-5"
                      v-model="user.oldPassword"
                      type="password"
                      placeholder="Введите старый пароль"
                      :class="{
                        'is-invalid': v.invalid && (v.touched || v.changed),
                        'is-valid': v.valid && v.dirty,
                      }"
                    ></b-form-input>
                    <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                      {{ v.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </ValidationProvider>
                <ValidationProvider
                  v-slot="v"
                  rules="min:8|atLeastOneDigAndSpec|atLeastLLetter|atLeastULetter|confirmed:confirmation"
                  class="w-50"
                  vid="confirmation"
                >
                  <b-form-group
                    id="input-group-6"
                    label-for="input-6"
                    description=""
                    class="pr-3 pt-3"
                  >
                    <b-form-input
                      id="input-6"
                      v-model="user.password"
                      type="password"
                      placeholder="Введите новый пароль"
                      :class="{
                        'is-invalid': v.invalid && (v.touched || v.changed),
                        'is-valid': v.valid && v.dirty,
                      }"
                    ></b-form-input>
                    <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                      {{ v.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </ValidationProvider>
                <ValidationProvider
                  v-slot="v"
                  rules="confirmed:confirmation|min:8"
                  class="w-50"
                >
                  <b-form-group
                    id="input-group-7"
                    label-for="input-7"
                    description=""
                    class="pr-3 pt-3"
                  >
                    <b-form-input
                      id="input-7"
                      v-model="user.repeatPassword"
                      type="password"
                      placeholder="Повторите пароль"
                      :class="{
                        'is-invalid': v.invalid && (v.touched || v.changed),
                        'is-valid': v.valid && v.dirty,
                      }"
                    ></b-form-input>
                    <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                      {{ v.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </ValidationProvider>
              </div>
              <div class="user-about-checkbox w-50 pr-3 pt-2">
                <b-form-checkbox
                  class="pb-1 pt-3"
                  :checked="validRegExp.min8.test(user.password)"
                  disabled
                >
                  Длинна пароля не менее 8 символов
                </b-form-checkbox>
                <b-form-checkbox
                  class="py-1"
                  :checked="validRegExp.oneLow.test(user.password)"
                  disabled
                >
                  Наличие строчных символов
                </b-form-checkbox>
                <b-form-checkbox
                  class="py-1"
                  :checked="validRegExp.oneUp.test(user.password)"
                  disabled
                >
                  Наличие заглавных символов
                </b-form-checkbox>
                <b-form-checkbox
                  class="py-1"
                  :checked="validRegExp.oneDigAndSpec.test(user.password)"
                  disabled
                >
                  Наличие цифр и спецсимволов
                </b-form-checkbox>
                <b-form-checkbox
                  class="py-1"
                  :checked="
                    user.password === user.repeatPassword &&
                    !!user.repeatPassword
                  "
                  disabled
                >
                  Пароли совпадают
                </b-form-checkbox>
              </div>
            </div>
            <b-button type="submit" class="ml-2 mr-3 my-3 background-purple">
              Сохранить
            </b-button>
            <b-button type="reset" class="mr-3 ml-3 my-3 background-purple">
              Сбросить
            </b-button>
          </b-form>
        </ValidationObserver>
      </b-col>
      <b-col lg="3" md="3" sm="3" xs="12" class="mt-3">
        <Avatar
          v-if="user.id"
          :id="user.id"
          :img-src="loggedInUser.images.preview"
          entity="user"
          behavior="avatarBehavior"
        />
      </b-col>
    </b-row>
  </b-container>
  </section>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import Loader from '@/components/Loader';
import CabinetNav from '@/components/cabinet/CabinetNav';
import config from '@/config/config';
import { mapActions, mapGetters } from 'vuex';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import Avatar from '@/components/cabinet/Avatar';

export default {
  name: 'About',
  components: {
    Avatar,
    CabinetNav,
    Breadcrumbs,
    ValidationObserver,
    ValidationProvider,
    Loader,
  },
  middleware: ['auth'],
  async fetch() {
    this.loading = true;
    try {
      this.user = await {
        ...this.$auth.user,
        oldPassword: '',
        password: '',
        repeatPassword: '',
      };
    } catch (e) {
      console.log(e);
    }
    
    this.breadcrumbs = [
      { label: 'Кабинет', url: '/cabinet' },
      { label: 'Моя информация', url: null },
    ];
    this.loading = false;
  },
  data: () => ({
    user: {},
    show: true,
    validRegExp: {
      min8: /^(?=.{8,})/,
      oneLow: /^(?=.*[a-zа-я])/,
      oneUp: /^(?=.*[A-ZА-Я])/,
      oneDigAndSpec: /^(?=.*[0-9])(?=.*[!@#$%^&*])/,
    },
    breadcrumbs: [],
    loading: false,
  }),
  computed: mapGetters(['loggedInUser']),
  methods: {
    ...mapActions(['setMessage']),
    async onSubmit() {
      this.loading = true;
      await this.$axios.post(`${config.api_url}/user/save`, {
        username: this.user.username,
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: this.user.password,
        oldPassword: this.user.oldPassword,
      }).then((response) => {
        this.setMessage(response.data.data.message[0]);
        this.$router.push('/cabinet/about');
        this.loading = false;
      }).catch((error) => {
        if (error.response && error.response.data) {
          this.loading = false;
          this.errors = error.response;
        }
      });
    },
    onReset(evt) {
      evt.preventDefault();
      // Reset our form values
      this.user.username = '';
      this.user.first_name = '';
      this.user.last_name = '';
      this.user.email = '';
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.user-form {
  font-size: 13px;
  input {

  }
}

.user-form-text {
  color: #999999;
  font-size: 11px;
  letter-spacing: 0;
}

.user-about-checkbox {
  line-height: 1.5rem;
  letter-spacing: 0;
}
</style>
