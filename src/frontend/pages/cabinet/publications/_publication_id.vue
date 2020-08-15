<template>
  <b-container class="cabinet-publications-edit py-3 vh-100">
    <Breadcrumbs :items="breadcrumbs"/>
    <b-row>
      <b-col lg="2" md="2" sm="3" xs="4">
        <CabinetNav />
      </b-col>
      <b-col lg="10" md="10" sm="9" xs="8">
         <div class="gallery">
          <div
            v-if="gallery"
            v-for="image in gallery"
            :key="image.id"
          >
            <img class="gallery-image" :src="image.preview"/>
          </div>
        </div>
        <vue2Dropzone
            ref="myVueDropzone"
            id="dropzone"
            v-if="isUpdate && dropzoneOptions.url"
            :useCustomSlot="true"
            :options="dropzoneOptions"
        >
          <div class="dropzone-custom-content">
            <div class="dropzone-custom-title text-small">
              Перетащите сюда фото либо выберите его на устройстве
            </div>
            <b-icon icon="image" class="mt-3 pic"></b-icon>
          </div>
        </vue2Dropzone>
        <div v-if="!isUpdate" class="text-center w-100 p-10 dropzone-custom-content">
          <div class="dropzone-custom-title text-small">
            Фото загружается после сохранения материала
          </div>
          <b-icon icon="image" class="mt-3 pic"></b-icon>
        </div>
        <b-form-input
            v-model="name"
            class="text-small mt-4"
            placeholder="Введите название"
        ></b-form-input>
        <b-form-textarea
            v-model="anons"
            class="text-small mt-4"
            placeholder="Введите короткое описание"
            rows="3"
        ></b-form-textarea>
        <VueEditor
            v-model="description"
            placeholder="Веедите полный текст публикации, добавляя ссылки и форматируя текст"
            class="mt-4"
        />
        <div class="text-right mt-4">
          <b-button
              class="background-purple mp-button-purple"
              @click="isUpdate ? update() : create()"
          >
            Сохранить публикацю
          </b-button>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import publications from '@/mixins/publications';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import { VueEditor } from "vue2-editor";
import { ModelStatuses } from '@/shared/constants';
import config from '@/config/config';

export default {
  name: 'Edit',
  components: {
    CabinetNav,
    Breadcrumbs,
    vue2Dropzone,
    VueEditor,
  },
  mixins: [publications],
  middleware: ['auth'],
  data() {
    return {
      user: null,
      publication: null,
      dropzoneOptions: {
        url: '',
        thumbnailWidth: 100,
        maxFilesize: 3,
        addRemoveLinks: true
      },
      name: '',
      description: '',
      anons: '',
      breadcrumbs: [],
      gallery: [],
    };
  },
  async fetch() {
    this.user = await this.$auth.user;
    if (this.isUpdate) {
      await this.getPublication(this.$route.params.publication_id, true);
      this.id = this.publication.id;
      this.name = this.publication.name;
      this.description = this.publication.description;
      this.anons = this.publication.anons;
      this.gallery = this.publication.gallery;
      this.dropzoneOptions.url = this.uploadUrl;
    }
  
    this.breadcrumbs = [
      { label: 'Кабинет', url: '/cabinet' },
      { label: 'Объявления', url: '/cabinet/publications'},
      { label: `${this.isUpdate ? 'Обновление публикации' : 'Создание публикации' }`, url: null }
    ];
  },
  computed: {
    isUpdate() {
      return this.$route.params.publication_id !== 'new';
    },
    uploadUrl() {
      return `${config.api_url}/news/galleryApi?type=news&behaviorName=galleryBehavior&galleryId=${this.id}&action=frontendUpload`;
    }
  },
  methods: {
    async create() {
      await this.createPublication({
        name: this.name,
        description: this.description,
        status: ModelStatuses.STATUS_NOT_PUBLISHED,
        anons: this.anons,
        user_id: this.user.id,
      });

      await this.$router.push({name: 'cabinet-publications'});
    },
    async update() {
      await this.updatePublication(this.$route.params.publication_id,
        {
        name: this.name,
        description: this.description,
        anons: this.anons,
      });

      await this.$router.push({name: 'cabinet-publications'});
    }
  }
}
</script>

<style lang="scss">
  .gallery {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    .gallery-image {
      width: 100%;
    }
  }
.cabinet-publications-edit {
  .dropzone {
    border: 1px dashed #999999;
    border-radius: 5px;

    .pic {
      font-size: 3.125rem;
    }
  }

  .ql-editor {
    @extend .text-small;

    &::before {
      font-style: normal !important;
      letter-spacing: normal !important;
    }
  }

  button {
    @extend .text-small;
  }
}
</style>
