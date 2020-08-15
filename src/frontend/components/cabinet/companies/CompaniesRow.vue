<template>
  <div class="company-row w-100 d-flex flex-row">
    <div class="image-cover">
      <img
          class="picture"
          :src="company.images.preview"
      />
    </div>
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
        <i class="fas fa-pencil-alt text-muted"/>
      </a>
      <a href="#" @click.prevent="deleteP">
        <b-icon class="text-muted" icon="trash-fill"/>
      </a>
    </div>
  </div>
</template>

<script>
  import {ModelStatuses} from '@/shared/constants';
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
          case(ModelStatuses.STATUS_MODERATION):
            return {
              color: 'text-orange',
              icon: 'eye',
              text: 'Модерация'
            };
          case(ModelStatuses.STATUS_NOT_PUBLISHED):
            return {
              color: 'text-gray',
              icon: 'eye-slash',
              text: 'Редактирование'
            };
          case(ModelStatuses.STATUS_PUBLISHED):
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
        if (this.company.status === ModelStatuses.STATUS_NOT_PUBLISHED) {
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
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.25);
    border-radius: 5px;
    .image-cover {
      width: 110px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f1f1;
      .picture {
        height: 100%;
      }
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
