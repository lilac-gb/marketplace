<template>
  <b-container class="cabinet-publications-edit mt-4 mb-4">
    <Breadcrumbs />
    <b-row>
      <b-col cols="3">
        <CabinetNav />
      </b-col>
      <b-col>
        <vue2Dropzone
          ref="myVueDropzone"
          id="dropzone"
          :useCustomSlot="true"
          :options="dropzoneOptions">
          <div class="dropzone-custom-content">
            <div class="dropzone-custom-title text-small">Перетащите сюда фото либо выберите его на устройстве</div>
            <b-icon icon="image" class="mt-3 pic"></b-icon>
          </div>
        </vue2Dropzone>
        <b-form-input
          v-model="name"
          class="text-small mt-4"
          placeholder="Введите название"/>
        <b-form-textarea
          v-model="anons"
          class="text-small mt-4"
          placeholder="Введите короткое описание"
          rows="3"/>
        <VueEditor
          v-model="description"
          placeholder="Веедите полный текст публикации, добавляя ссылки и форматируя текст"
          class="mt-4"/>
        <div class="text-right mt-4">
          <b-button class="background-purple mp-button-purple" @click="isUpdate ? update() : create()">
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
import { NewsStatuses } from '@/shared/constants';

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
  async fetch() {
    this.user = await this.$auth.user;

    if (this.isUpdate) {
      await this.updatePublication(this.$route.params.publication_id, true);
      this.name = this.publication.name;
      this.description = this.publication.description;
      this.anons = this.publication.anons;
    }
  },
  data() {
    return {
      user: null,
      publication: null,
      dropzoneOptions: {
        url: 'https://httpbin.org/post',
        thumbnailWidth: 150,
        maxFilesize: 0.5,
      },
      name: '',
      description: '',
      anons: '',
    };
  },
  computed: {
    isUpdate() {
      return this.$route.params.publication_id.toLowerCase() !== 'new';
    }
  },
  methods: {
    async create() {
      await this.createPublication({
        name: this.name,
        description: this.description,
        status: NewsStatuses.STATUS_NOT_PUBLISHED,
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