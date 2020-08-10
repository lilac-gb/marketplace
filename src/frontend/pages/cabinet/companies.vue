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
              <b-form-group
                id="input-group-8"
                label-for="input-8"
                description=""
                class="pr-3 pt-3 w-100"
              >
                <multiselect
                  v-model="weekDay"
                  placeholder="Выберите рабочие дни"
                  select-label="Нажмите ввод для выбора"
                  selected-label=""
                  max-height="300"
                  option-height="40"
                  deselect-label="Нажмите ввод для удаления"
                  label="day"
                  track-by="dayNumber"
                  :close-on-select="false"
                  :options="weekDayOptions"
                  :multiple="true"
                  :taggable="true"
                  @tag="addTag"
                />
                <!--                <pre class="language-json"><code>{{ weekDay }}</code></pre>-->
              </b-form-group>
              <b-form-group
                id="input-group-9"
                label-for="input-9"
                description=""
                class="pr-3 w-50"
              >
                <b-form-timepicker v-model="company.workFrom" locale="de" />
              </b-form-group>
              <b-form-group
                id="input-group-10"
                label-for="input-10"
                description=""
                class="pr-3 w-50"
              >
                <b-form-timepicker v-model="company.workTo" locale="de" />
              </b-form-group>
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
import multiselect from 'vue-multiselect';
export default {
  name: 'Companies',
  components: {
    multiselect,
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
    weekDay: null,
    weekDayOptions: [
      { dayNumber: 1, day: 'ПН' },
      { dayNumber: 2, day: 'ВТ' },
      { dayNumber: 3, day: 'СР' },
      { dayNumber: 4, day: 'ЧТ' },
      { dayNumber: 5, day: 'ПТ' },
      { dayNumber: 6, day: 'СБ' },
      { dayNumber: 7, day: 'ВС' },
    ],
    company: {},
    user: {},
    show: true,
  }),
  computed: {},
  methods: {
    ...mapActions(['setMessage']),
    addTag(weekDay) {
      const day = {
        day: weekDay,
        dayNumber:
          weekDay.substring(0, 2) + Math.floor(Math.random() * 10000000),
      };
      this.options.push(day);
      this.value.push(day);
    },
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss">
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
.multiselect {
  font-size: 13px;
  line-height: 1;
  &__spinner {
    &:before,
    &:after {
      border-color: $purple transparent transparent;
    }
  }
}
.multiselect__input::placeholder {
  color: #35495e;
}

.multiselect__input:hover,
.multiselect__single:hover {
  border-color: $light-gray;
}

.multiselect__input:focus,
.multiselect__single:focus {
  border-color: $light-gray;
}

.multiselect__tags {
  border: 1px solid $light-gray;
  min-height: 34px;
}

.multiselect__tag {
  background: $purple;
}

.multiselect__tag-icon:after {
  color: #7a3fa5;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #5e369a;
}

.multiselect__select:before {
  color: #999;
  top: 75%;
  border-width: 15px 8px 0 8px;
  border-color: $light-gray transparent transparent transparent;
}

.multiselect__placeholder {
  color: #495057;
  font-size: 13px;
  font-weight: 200;
  letter-spacing: 0;
  padding-left: 2px;
}

.multiselect__content-wrapper {
  border: 1px solid $light-gray;
}

.multiselect--above .multiselect__content-wrapper {
  border-top: 1px solid $light-gray;
}

.multiselect__option--highlight {
  background: $purple;
}

.multiselect__option--highlight:after {
  background: $purple;
}

.multiselect__option--selected {
  background: #f3f3f3;
  color: #35495e;
}

.multiselect__option--selected:after {
  color: silver;
}

.multiselect__option--selected.multiselect__option--highlight {
  background: #ff6a6a;
}

.multiselect__option--selected.multiselect__option--highlight:after {
  background: #ff6a6a;
}

.multiselect--disabled .multiselect__current,
.multiselect--disabled .multiselect__select {
  background: #ededed;
  color: #a6a6a6;
}

.multiselect__option--disabled {
  background: #ededed !important;
  color: #a6a6a6 !important;
}
</style>
