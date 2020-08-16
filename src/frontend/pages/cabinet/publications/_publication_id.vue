<template>
  <section id="cabinet-publication-edit" class="min-vh-100 mh-100">
    <div v-if="loading" class="main-loader">
      <Loader />
    </div>
    <b-container class="cabinet-publications-edit py-3 vh-100">
    <Breadcrumbs :items="breadcrumbs" />
    <b-row>
      <b-col lg="2" md="2" sm="3" xs="4">
        <CabinetNav />
      </b-col>
      <b-col lg="10" md="10" sm="9" xs="8">
        
        <carousel
          v-if="gallery && isUpdate"
          class="gallery"
          paginationActiveColor="#4D0685"
          :perPageCustom="[[480, 2], [768, 2], [1024, 3]]"
        >
          <slide
            class="gallery-slide"
            v-for="image in gallery"
            :key="image.id"
          >
            <img class="gallery-image" :src="image.preview" />
             <a class="remove-image" href="#" @click.prevent="deleteImage(image.id)">
               <b-icon icon="trash-fill"></b-icon>
             </a>
          </slide>
        </carousel>
        <input multiple="multiple" @change="addFormFiles" type="file" id="fileUpload" class="hidden" />
        <div class="dropzone" @click.prevent="showUpload" v-cloak @drop.prevent="addFile" @dragover.prevent>
           <div class="dropzone-info">
            <div class="dropzone-title text-small">
              Перетащите сюда фото либо выберите его на устройстве
            </div>
            <b-icon icon="image" class="mt-3 ic"></b-icon>
          </div>
          <ul>
            <li class="text-muted" v-for="file in files">
              {{ file.name }} ({{(file.size / 1048576).toFixed(2) }} мб)
              <b-button variant="link" @click="removeFile(file)" title="Remove">&times;</b-button>
            </li>
          </ul>
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
            class="mp-btn mp-btn-purple"
            @click="isUpdate ? update() : create()"
          >
            Сохранить публикацю
          </b-button>
        </div>
      </b-col>
    </b-row>
  </b-container>
  </section>
</template>

<script>
import Breadcrumbs from '@/components/Breadcrumbs';
import { Carousel, Slide } from 'vue-carousel';
import Loader from '@/components/Loader';
import CabinetNav from '@/components/cabinet/CabinetNav';
import publications from '@/mixins/publications';
import { VueEditor } from 'vue2-editor';
import { mapGetters } from 'vuex';
import { ModelStatuses } from '@/shared/constants';
import config from '@/config/config';

export default {
  name: 'Edit',
  components: {
    CabinetNav,
    Breadcrumbs,
    VueEditor,
    Loader,
    Carousel,
    Slide,
  },
  mixins: [publications],
  middleware: ['auth'],
  data() {
    return {
      user: null,
      publication: null,
      files: [],
      name: '',
      description: '',
      anons: '',
      breadcrumbs: [],
      gallery: [],
      loading: false,
      fileSize: 2000000, //2mb
      imageMimTypes: [
        'image/png',
        'image/jpeg',
        'image/pjpeg',
      ],
    };
  },
  async fetch() {
    this.loading = true;
    this.user = await this.$auth.user;
    if (this.isUpdate) {
      await this.getPublication(this.$route.params.publication_id, true);
      this.id = this.publication.id;
      this.name = this.publication.name;
      this.description = this.publication.description;
      this.anons = this.publication.anons;
      this.gallery = this.publication.gallery;
    }
  
    this.breadcrumbs = [
      { label: 'Кабинет', url: '/cabinet' },
      { label: 'Объявления', url: '/cabinet/publications' },
      { label: `${this.isUpdate ? 'Обновление публикации' : 'Создание публикации' }`, url: null },
    ];
    this.loading = false;
  },
  computed: {
    ...mapGetters(['loggedInUser']),
    isUpdate() {
      return this.$route.params.publication_id !== 'new';
    },
  },
  methods: {
    showUpload() {
      const uploadForm = document.getElementById('fileUpload');
      uploadForm.click();
    },
    addFormFiles(e) {
      if (e.target.files) {
        let droppedFiles = e.target.files;
        if (!droppedFiles) return;
        ([...droppedFiles]).forEach(f => {
          if (this.validateFile(f)) {
            this.files.push(f);
          }
        });
      }
    },
    validateFile(file) {
      return this.imageMimTypes.includes(file.type) && file.size < this.fileSize;
    },
    addFile(e) {
      let droppedFiles = e.dataTransfer.files;
      if (!droppedFiles) return;
      ([...droppedFiles]).forEach(f => {
        if (this.validateFile(f)) {
          this.files.push(f);
        }
      });
    },
    removeFile(file) {
      this.files = this.files.filter((f) => f !== file);
    },
    upload(id = null) {
      this.files.forEach(async(file) => {
        let formData = new FormData();
        formData.append('file', file);
        await this.$axios.post(
          `${config.api_url}/news/galleryApi?type=news&behaviorName=galleryBehavior&galleryId=${id
            ? id
            : this.id}&action=frontendUpload`,
          formData,
        );
        await this.$fetch();
        this.files = this.files.filter((f) => f !== file);
      });
    },
    async deleteImage(id) {
      let formData = new FormData();
      formData.append('id[]', id);
      await this.$axios.post(
        `${config.api_url}/news/galleryApi?type=news&behaviorName=galleryBehavior&galleryId=${this.id}&action=delete`,
        formData,
      );
      this.$fetch();
    },
    async create() {
      const result = await this.createPublication({
        name: this.name,
        description: this.description,
        status: ModelStatuses.STATUS_NOT_PUBLISHED,
        anons: this.anons,
        user_id: this.user.id,
      });
  
      if (result.data.id) {
        await this.upload(result.data.id);
        
        this.$router.push({
          name: 'cabinet-publications-publication_id',
          params: {
            publication_id: result.data.id,
          },
        });
      }
    },
    async update() {
      await this.upload(this.id);
      
      await this.updatePublication(this.$route.params.publication_id,
        {
          name: this.name,
          description: this.description,
          anons: this.anons,
        },
      );
    },
  },
};
</script>

<style lang="scss">
  .hidden {
    display: none;
    visibility: hidden;
  }
  .gallery {
    .gallery-slide {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #ddd;
      margin: 0 5px;
    }
    .gallery-image {
      height: 150px;
      max-height: 100%;
    }
    .remove-image {
      position: absolute;
      top: 10px;
      right: 10px;
      text-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
      color: #fff;
      &:hover {
        color: $purple;
      }
    }
  }

  .cabinet-publications-edit {
    .dropzone {
      min-height: 100px;
      border: 1px dashed #999999;
      border-radius: 5px;
      padding: 20px;
      margin-top: 30px;
      .dropzone-info {
        color: #626262;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
      }
      .ic {
        font-size: 2.125rem;
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
