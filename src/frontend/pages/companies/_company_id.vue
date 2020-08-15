<template>
  <section id="company-page">
    <b-container class="mt-4 mb-4">
      <div v-if="company">
        <Breadcrumbs :items="breadcrumbs" class="d-flex" />
        <div class="mb-5 text-title">{{ company.name }}</div>
        <div class="d-flex flex-row">
          <div class="card-details mr-4">
            <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
              <img
                class="item-img"
                v-if="company.images"
                :src="company.images.original"
              />
            </div>
            <label class="mt-2 mb-2 text-muted">ВРЕМЯ РАБОТЫ</label>
            <div
              v-if="company.working_days"
              class="text-muted mb-2"
            >
              {{ getObjectDays(company.working_days) }}
            </div>
            <div class="d-flex align-items-center">
              <div
                class="text-muted mr-1"
                v-if="company.time_from"
              >
              С {{ company.time_from }}
            </div>
            <div
              class="text-muted"
              v-if="company.time_to"
            >
              до {{ company.time_to }}
            </div>
            </div>
               <div
                 v-if="!!company.user"
                 class="text-muted mt-2 icon user"
               >
                 {{ company.user.name }}
               </div>
            <div class="text-muted mt-2 icon views">
              {{ company.views }}
            </div>
            <div class="d-flex flex-row mt-5">
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'facebook-square']" />
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'twitter-square']" />
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'instagram-square']" />
              </a>
              <a href="#" class="social-link">
                <font-awesome-icon :icon="['fab', 'whatsapp-square']" />
              </a>
            </div>
          </div>
          <div class="text-regular" v-html="company.description"></div>
        </div>
      </div>
    </b-container>
  </section>
</template>

<script>
  import companies from '@/mixins/companies';
  import utils from '@/mixins/utils';
  import { ModelParams, SortDirection } from '@/shared/constants';
  import Breadcrumbs from '@/components/Breadcrumbs';

  export default {
    name: 'Company',
    components: {
      Breadcrumbs,
    },
    mixins: [
      companies,
      utils,
    ],
    async fetch() {
      await Promise.all([
        this.getCompany(this.$route.params.company_id, true),
        this.getCompanies(this.companysApiParams),
      ]);
      this.breadcrumbs = [
        { label: 'Компании', url: '/companies' },
        { label: this.company ? this.company.name : 'Компания', url: null },
      ];
    },
    data() {
      return {
        companies: [],
        company: null,
        currentPage: 1,
        pageCount: 1,
        perPage: 4,
        totalCount: null,
        sortBy: ModelParams.CREATED_AT,
        sortDesc: SortDirection.ASK,
        breadcrumbs: [],
        weekDayOptions: [
          { dayNumber: 0, day: 'ПН' },
          { dayNumber: 1, day: 'ВТ' },
          { dayNumber: 2, day: 'СР' },
          { dayNumber: 3, day: 'ЧТ' },
          { dayNumber: 4, day: 'ПТ' },
          { dayNumber: 5, day: 'СБ' },
          { dayNumber: 6, day: 'ВС' },
        ],
      };
    },
    computed: {
      companysApiParams() {
        return {
          page: this.currentPage,
          pageSize: this.perPage,
          sortBy: this.sortBy,
          sortDesc: this.sortDesc,
        };
      },
    },
    methods: {
      getObjectDays(string) {
        let result = [];
        if (string) {
          let indexes = string.split(',');
          for (let item in this.weekDayOptions) {
            if (Object.values(indexes).includes(item)) {
              result.push(this.weekDayOptions[item].day);
            }
          }
        }
    
        return result.join(', ');
      },
      async goRight() {
        if (this.currentPage !== this.pageCount) {
          this.currentPage += 1;
          await this.getCompanies(this.companysApiParams);
        }
      },
      async goLeft() {
        if (this.currentPage !== 1) {
          this.currentPage -= 1;
          await this.getCompanies(this.companysApiParams);
        }
      },
    },
  };
</script>

<style lang="scss" scoped>
  .item-img {
    max-width: 300px;
    max-height: 300px;
    border-radius: 50%;
    margin-bottom: 15px;
  }

  .button-scroll {
    font-size: 50px;
  }

  a.disabled {
    pointer-events: none;
    color: $gray;
  }

  .social-link {
    display: block;
    font-size: 32px;
    margin-right: 34px;
    color: $gray;
  
    &:last-of-type {
      margin-right: 0;
    }
  }

  .big-image-card {
    max-width: none !important;
  }
</style>
