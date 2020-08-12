<template>
  <div class="company-row w-100 d-flex flex-row">
    <div
        class="picture"
        :style="{ backgroundImage: `url(${company.images.preview})` }"></div>
    <div class="text d-flex align-items-center">{{ company.name }}</div>
    <div class="info d-flex align-items-center justify-content-end">
      <a href="#" @click.prevent="publicate">
        <b-icon
            :class="publishState.color"
            :icon="publishState.icon"
            v-b-tooltip.hover
            :title="publishState.text"/>
      </a>
      <a href="#" @click.prevent="edit">
        <i class="fas fa-pencil-alt text-purple"/>
      </a>
      <a href="#" @click.prevent="deleteP">
        <b-icon class="text-red" icon="trash-fill"/>
      </a>
    </div>
  </div>
</template>

<script>
  import {NewsStatuses} from '@/shared/constants';
  import companies from '@/mixins/companies';

  export default {
    name: 'CompaniesRow',
    mixins: [companies],
    props: {
      company: {type: Object, required: true}
    },
    computed: {
      publishState() {
        switch (this.company.status) {
          case(NewsStatuses.STATUS_MODERATION):
            return {
              color: 'text-yelow',
              icon: 'eye',
              text: 'Модерация'
            };
          case(NewsStatuses.STATUS_NOT_PUBLISHED):
            return {
              color: 'text-gray',
              icon: 'eye-slash',
              text: 'Редактирование'
            };
          case(NewsStatuses.STATUS_PUBLISHED):
            return {
              color: 'text-purple',
              icon: 'eye',
              text: 'Опубликовано'
            }
        }
      }
    },
    methods: {
      publicate() {
        if (this.company.status === NewsStatuses.STATUS_NOT_PUBLISHED) {
          this.publishCompany(this.company.id);
        }
        this.$emit('updated');
      },
      deleteCompany() {
        this.deleteCompany(this.company.id);
        this.$emit('updated');
      },
      edit() {
        this.$router.push({
          name: 'cabinet-companies-company_id',
          params: {
            company_id: this.company.id,
          },
        });
      }
    }
  }
</script>

<style lang="scss">
  .company-row {
    height: 5.0625rem;
    background: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    border-radius: 5px;

    .picture {
      width: 8.5rem;
      height: 100%;
      display: inline-block;
    }

    .text {
      flex: 1;
      height: 100%;
      padding: 0 22px 0 22px;
    }

    .info {
      height: 100%;
      padding: 0 28px 0 0;

      a {
        margin-left: 20px;

        i::before,
        .b-icon {

          height: 1.5625rem;
          width: 1.25rem;
          font-size: 1.25rem;
        }
      }

      a:first-of-type {
        margin-left: 0;
      }
    }
  }
</style>