<template>
  <b-form @submit.prevent="$emit('onSubmit', search)">
    <b-row class="align-items-stretch mb-4">
      <b-col md="8">
        <b-input
          v-model.trim="value"
          placeholder="Поиск"
          class="mp-input w-100"
        ></b-input>
      </b-col>

      <b-col md="2">
        <b-button type="submit" class="background-purple h-100 w-100"
          >Поиск</b-button
        >
      </b-col>

      <b-col md="2">
        <b-button
          v-b-toggle.filter
          class="background-purple h-100 w-100 collapse-button"
          >Фильтр</b-button
        >
      </b-col>
    </b-row>

    <b-collapse id="filter" class="filter-settings">
      <slot></slot>
    </b-collapse>
  </b-form>
</template>

<script>
export default {
  name: 'SearchForm',
  data: () => ({
    value: '',
  }),
};
</script>

<style scoped lang="scss">
.search-btn {
  height: 100%;
}
</style>
