<template>
  <b-container class="py-3">
    <Breadcrumbs />
    <b-row>
      <b-col lg="3" md="6" sm="6" xs="12">
        <CabinetNav />
      </b-col>
      <b-col lg="6" md="6" sm="6" xs="12">
        <ValidationObserver v-slot="{ handleSubmit }">
          <b-form
            v-if="show"
            class="companies-form d-flex flex-row flex-wrap"
            @submit.prevent="handleSubmit(onSubmit)"
            @reset.prevent="onReset"
          >
            <div class="d-flex flex-row flex-wrap">
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:100|required"
                class="w-100"
              >
                <b-form-group
                  id="input-group-1"
                  label-for="input-1"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-1"
                    v-model="company.name"
                    placeholder="Названиие компании"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  />
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:1000"
                class="w-100"
              >
                <b-form-group
                  id="input-group-2"
                  label-for="input-2"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-textarea
                    id="input-2"
                    v-model="company.description"
                    placeholder="Описание компании"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  />
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider
                v-slot="v"
                rules="inn:10|required"
                class="w-100"
              >
                <b-form-group
                  id="input-group-4"
                  label-for="input-4"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-4"
                    v-model="company.inn"
                    required
                    placeholder="ИНН"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  />
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider
                v-slot="v"
                rules="ogrn:13|required"
                class="w-100"
              >
                <b-form-group
                  id="input-group-5"
                  label-for="input-5"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-5"
                    v-model="company.ogrn"
                    placeholder="ОГРН"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  />
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider v-slot="v" rules="tel" class="w-100">
                <b-form-group
                  id="input-group-6"
                  label-for="input-6"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-6"
                    v-model="company.tel"
                    type="tel"
                    placeholder="Телефон"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  />
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:100|email"
                class="w-100"
              >
                <b-form-group
                  id="input-group-7"
                  label-for="input-7"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-7"
                    v-model="company.email"
                    type="email"
                    placeholder="Email"
                    :class="{
                      'is-invalid': v.invalid && (v.touched || v.changed),
                      'is-valid': v.valid && v.dirty,
                    }"
                  />
                  <b-form-invalid-feedback :class="{ 'd-block': v.errors }">
                    {{ v.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </ValidationProvider>
            </div>
            <b-button type="submit" class="ml-2 mr-3 my-3 background-purple">
              Сохранить информацию
            </b-button>
            <b-button type="reset" class="mr-3 ml-3 my-3 background-purple">
              Сбросить
            </b-button>
          </b-form>
        </ValidationObserver>
      </b-col>
      <b-col lg="3" md="12" sm="12" xs="12" class="mt-3">
        <Avatar />
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import config from '@/config/config';
import { mapActions } from 'vuex';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import Avatar from '@/components/cabinet/Avatar';
export default {
  name: 'Companies',
  components: {
    Avatar,
    CabinetNav,
    Breadcrumbs,
    ValidationObserver,
    ValidationProvider,
  },
  middleware: ['auth'],
  async fetch() {
    try {
      this.user = await {
        ...this.$auth.user,
      };
    } catch (e) {
      console.log(e);
    }
  },
  data: () => ({
    company: {},
    user: {},
    show: true,
  }),
  computed: {},
  methods: {
    ...mapActions(['setMessage']),
    async onSubmit() {
      this.loading = true;
      await this.$axios
        .post(`${config.api_url}/user/save`, {
          username: this.user.username,
          first_name: this.user.first_name,
          last_name: this.user.last_name,
          email: this.user.email,
          password: this.user.password,
          oldPassword: this.user.oldPassword,
        })
        .then((response) => {
          this.setMessage(response.data.data.message[0]);
          this.$router.push('/cabinet/about');
        })
        .catch((error) => {
          if (error.response && error.response.data) {
            this.loading = false;
            //console.log(error.response);
            this.errors = error.response;
          }
        });
    },
    onReset() {
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
.companies-form {
  font-size: 13px;
  input {
    font-size: 13px;
  }
  textarea.form-control {
    font-size: 13px;
  }
}
.companies-form-text {
  color: #999999;
  font-size: 11px;
  letter-spacing: 0;
}
</style>
