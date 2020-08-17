<template>
  <div class="card d-flex flex-column justify-content-between">
    <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
      <img
        class="company-img"
        :src="company.images.original"
      />
    </div>
    <b-card-text class="px-4">
      <router-link
        class="text-dark"
        :to="{
          name: 'companies-company_id',
          params: { company_id: company.url }
         }"
      >
        {{ company.name }}
      </router-link>
    </b-card-text>
    <div class="px-4 pb-4 text-muted">
      <div v-if="!!company.user" class="icon user">
        {{ company.user.name }}
      </div>
      <div class="icon create-at">
        {{ timestampToDate(company.created_at) }}
      </div>
      <div class="icon views">
        {{ company.views }}
      </div>
    </div>
  </div>
</template>

<script>
  import utils from '@/mixins/utils';

  export default {
    name: 'CompaniesCard',
    props: {
      company: { type: Object, required: true },
    },
    mixins: [utils],
  };
</script>

<style lang="scss">
  #companies {
    .company-img {
      max-width: 300px;
      max-height: 300px;
      border-radius: 50%;
      margin-bottom: 15px;
    }
  }
</style>
