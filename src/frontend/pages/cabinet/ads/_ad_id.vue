<template>
  <b-container class="cabinet-ads-edit mt-4 mb-4">
    <Breadcrumbs :items="breadcrumbs" />
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
            <img class="gallery-image" :src="image.preview" />
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
        <b-form-input
          v-model="price"
          class="text-small mt-4"
          placeholder="Введите стоимость"
        ></b-form-input>
        <b-form-input
          v-model="url_site"
          class="text-small mt-4 mb-4"
          placeholder="URL сайта"
        ></b-form-input>
        <b-row>
          <b-col lg="6">
              <b-form-select
                v-model="section_id"
                :options="sections"
                class="w-100"
              ></b-form-select>
            </b-col>
            <b-col lg="6">
              <b-form-select
                v-model="type_id"
                :options="types"
                class="w-100"
              ></b-form-select>
            </b-col>
        </b-row>
        <VueEditor
          v-model="description"
          placeholder="Веедите полный текст объявления, добавляя ссылки и форматируя текст"
          class="mt-4"
        />
        <div class="text-right mt-4">
          <b-button
            class="mp-btn mp-btn-purple"
            @click="isUpdate ? update() : create()"
          >
            {{ isUpdate ? 'Сохранить объявление' : 'Обновить объявление' }}
          </b-button>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import CabinetNav from '@/components/cabinet/CabinetNav';
import ads from '@/mixins/ads';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import { VueEditor } from 'vue2-editor';
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
  mixins: [ads],
  middleware: ['auth'],
  data() {
    return {
      user: null,
      ad: null,
      dropzoneOptions: {
        url: '',
        thumbnailWidth: 100,
        maxFilesize: 3,
        addRemoveLinks: true,
      },
      type_id: 1,
      section_id: 1,
      name: '',
      price: '',
      description: '',
      ended_at: '',
      life_time: '',
      url_site: '',
      status: 0,
      user_id: null,
      gallery: [],
      breadcrumbs: [],
    };
  },
  async fetch() {
    await Promise.all([
      this.getSections(),
      this.getTypes(),
    ]);
    this.user = await this.$auth.user;
    if (this.isUpdate) {
      try {
        await this.getAd(this.$route.params.ad_id, false);
        this.id = this.ad.id;
        this.type_id = this.ad.type_id;
        this.section_id = this.ad.section_id;
        this.name = this.ad.name;
        this.price = this.ad.price;
        this.description = this.ad.description;
        this.ended_at = this.ad.ended_at;
        this.life_time = this.ad.life_time;
        this.url_site = this.ad.url_site;
        this.gallery = this.ad.gallery ? this.ad.gallery : [];
        this.dropzoneOptions.url = this.uploadUrl;
      } catch (err) {
        console.log(err);
      }
    }
    
    this.breadcrumbs = [
      { label: 'Кабинет', url: '/cabinet' },
      { label: 'Объявления', url: '/cabinet/ads' },
      { label: `${this.isUpdate ? 'Обновление объявления' : 'Создание объявления' }`, url: null },
    ];
  },
  computed: {
    isUpdate() {
      return this.$route.params.ad_id !== 'new';
    },
    uploadUrl() {
      return `${config.api_url}/ad/galleryApi?type=ad&behaviorName=galleryBehavior&galleryId=${this.id}&action=frontendUpload`;
    },
  },
  methods: {
    async create() {
      await this.createAd({
        type_id: this.type_id,
        section_id: this.section_id,
        name: this.name,
        price: this.price,
        description: this.description,
        ended_at: this.ended_at,
        life_time: this.life_time,
        url_site: this.url_site,
        status: ModelStatuses.STATUS_NOT_PUBLISHED,
        user_id: this.user.id,
      });
      
      await this.$router.push({ name: 'cabinet-ads' });
    },
    async update() {
      await this.updateAd(this.$route.params.ad_id,
        {
          type_id: this.type_id,
          section_id: this.section_id,
          name: this.name,
          price: this.price,
          description: this.description,
          ended_at: this.ended_at,
          life_time: this.life_time,
          url_site: this.url_site,
          status: ModelStatuses.STATUS_NOT_PUBLISHED,
        },
      );
      
      await this.$router.push({ name: 'cabinet-ads' });
    },
  },
};
</script>

<style lang="scss">
  .gallery {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    .gallery-image {
      width: 100%;
    }
  }

  .cabinet-ads-edit {
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
