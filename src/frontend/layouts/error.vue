<template>
  <div class="vh-100 d-flex justify-content-center align-items-center">
    <div class="w-25">
      <h1 v-if="error.statusCode === 404">Ошибка 404</h1>
      {{ error.statusCode }}
      <h2>Страница не найдена</h2>
    <NuxtLink to="/">На главную</NuxtLink>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'default',
  props: ['error'],
  async init() {
    console.log(this.error);
  },
};
</script>
