<template>
  <div class="vh-100 d-flex justify-content-center align-items-center">
    <div class="w-50">
      <div v-if="error.statusCode === 404">
        <h1>Ошибка {{ error.statusCode }}</h1>
        <h2>Страница не найдена, если она нужна, то мы обязательно скоро ее сделаем, а пока перейдите
          <NuxtLink to="/">на главную</NuxtLink>
        </h2>
      </div>
      <div v-if="error.statusCode === 500">
        <h1>Ошибка {{ error.statusCode }}</h1>
        <h2>
          Что-то пошло не так, но мы уже в курсе, а пока просто перейдите
          <NuxtLink to="/">на главную</NuxtLink>
        </h2>
      </div>
      <div v-if="error.statusCode === 403">
        <h1>Ошибка {{ error.statusCode }}</h1>
        <h2>
          Куда-то вы не туда забрели, мы Вам поможем, просто перейдите
          <NuxtLink to="/">на главную</NuxtLink>
        </h2>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'default',
  props: ['error'],
  async fetch({res}) {
    console.log(this.error);
    res.statusCode = error.statusCode;
  },
};
</script>
