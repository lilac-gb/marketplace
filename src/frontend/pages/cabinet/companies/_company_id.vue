<template>
  <b-container class="py-3">
    <Breadcrumbs />
    <b-row>
      <b-col lg="2" md="3" sm="3" xs="12">
        <CabinetNav />
      </b-col>
      <b-col lg="7" md="5" sm="5" xs="12">
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
                      v-model="company.vat"
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
                      v-model="company.id_number"
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
                      v-model="company.phone"
                      type="phone"
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
              <ValidationProvider
                v-slot="v"
                rules="min:2|max:100|site"
                class="w-100"
              >
                <b-form-group
                  id="input-group-8"
                  label-for="input-8"
                  description=""
                  class="pr-3 pt-3"
                >
                  <b-form-input
                    id="input-8"
                    v-model="company.site"
                    placeholder="Сайт компании"
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
                id="input-group-9"
                label-for="input-9"
                description=""
                class="pr-3 pt-3 w-100"
              >
                <multiselect
                  v-model="company.weekDay"
                  form="input-9"
                  placeholder="Выберите рабочие дни"
                  select-label="Нажмите ввод для выбора"
                  selected-label=""
                  :max-height="300"
                  :option-height="40"
                  deselect-label="Нажмите ввод для удаления"
                  label="day"
                  track-by="dayNumber"
                  :close-on-select="false"
                  :options="weekDayOptions"
                  :multiple="true"
                  :taggable="true"
                  @tag="addTag"
                />
                <pre
                  class="language-json"
                ><code>{{ company.weekDay }}</code></pre>
              </b-form-group>
              <b-form-group
                id="input-group-10"
                label-for="input-10"
                description=""
                class="pr-3 w-50"
              >
                <b-form-timepicker
                    v-model="company.time_from"
                    locale="de"
                    form="input-10"
                    label-no-time-selected="Начало работы"
                    no-close-button
                    class="time"
                />
              </b-form-group>
              <b-form-group
                id="input-group-11"
                label-for="input-11"
                description=""
                class="pr-3 w-50"
              >
                <b-form-timepicker
                    v-model="company.time_to"
                    locale="de"
                    form="input-11"
                    label-no-time-selected="Окончание работы"
                    no-close-button
                    class="time"
                />
              </b-form-group>
            </div>
            <b-button @click="isUpdate ? update() : create()" class="ml-2 mr-3 my-3 background-purple">
              Сохранить информацию
            </b-button>
            <b-button type="reset" class="mr-3 ml-3 my-3 background-purple">
              Сбросить
            </b-button>
          </b-form>
        </ValidationObserver>
      </b-col>
      <b-col lg="3" md="4" sm="4" xs="12" class="mt-3">
        <Avatar
            v-if="company.id"
            :id="company.id"
            entity="company"
            :img-src="company.images.preview"
            behavior="logoBehavior"
        />
        <div v-else="!company.id">
          Фото можно сохранить только после создания компании
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import companies from '@/mixins/companies';
import config from '@/config';
import { mapActions } from 'vuex';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import Avatar from '@/components/cabinet/Avatar';
import multiselect from 'vue-multiselect';

export default {
  name: 'Edit',
  components: {
    multiselect,
    Avatar,
    CabinetNav,
    Breadcrumbs,
    ValidationObserver,
    ValidationProvider,
  },
  middleware: ['auth'],
  mixins: [companies],
  data() {
    return {
      weekDay: null,
      weekDayOptions: [
        {dayNumber: 1, day: 'ПН'},
        {dayNumber: 2, day: 'ВТ'},
        {dayNumber: 3, day: 'СР'},
        {dayNumber: 4, day: 'ЧТ'},
        {dayNumber: 5, day: 'ПТ'},
        {dayNumber: 6, day: 'СБ'},
        {dayNumber: 7, day: 'ВС'},
      ],
      company: {
        id: '',
        name: '',
        description: '',
        vat: '',
        id_number: '',
        phone: '',
        email: '',
        weekDay: '',
        time_from: '',
        time_to: '',
      },
      user: {},
      show: true,
    }
  },
  async fetch() {
    this.user = await this.$auth.user;
    if (this.isUpdate) {
      await this.getCompany(this.$route.params.company_id, true);
      console.log(this.company);
      this.id = this.company.id;
      this.name = this.company.name;
      this.description = this.company.description;
      this.vat = this.company.vat;
      this.id_number = this.company.id_number;
      this.phone = this.company.phone;
      this.site = this.company.site;
      this.email = this.company.email;
      this.weekDay = this.company.working_days;
      this.time_from = this.company.time_from;
      this.time_to = this.company.time_to;
    }
  },
  computed: {
    isUpdate() {
      return this.$route.params.company_id !== 'new';
    },
  },
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
    async create() {
      this.loading = true;
      await this.createCompany({
        name: this.company.name,
        description: this.company.description,
        owner_id: this.user.id,
        site: this.company.site,
        phone: this.company.phone,
        email: this.company.email,
        id_number: this.company.id_number,
        vat: this.company.vat,
        working_days: !!this.company.weekDay.length ? this.company.weekDay
          .map((day) => day.dayNumber)
          .join(',') : '',
        time_from: this.company.time_from,
        time_to: this.company.time_to,
      });
      this.loading = false;
    },
    async update() {
      this.loading = true;
      await this.updateCompany(this.$route.params.company_id, {
        name: this.company.name,
        description: this.company.description,
        owner_id: this.user.id,
        site: this.company.site,
        phone: this.company.phone,
        email: this.company.email,
        id_number: this.company.id_number,
        vat: this.company.vat,
        working_days: !!this.company.weekDay.length ? this.company.weekDay
          .map((day) => day.dayNumber)
          .join(',') : '',
        time_from: this.company.time_from,
        time_to: this.company.time_to,
      });
      this.loading = false;
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
<style
  src="@/node_modules/vue-multiselect/dist/vue-multiselect.min.css"
></style>
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
  &__spvater {
    &:before,
    &:after {
      border-color: $purple transparent transparent;
    }
  }
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

.multiselect__input::placeholder {
  color: #495057;
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
  background: $red;
}

.multiselect__option--selected.multiselect__option--highlight:after {
  background: $red;
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

.time {
  font-size: 13px;
  letter-spacing: 0;
  color: #495057;
  & .btn:hover {
    color: $purple;
  }
}
</style>
