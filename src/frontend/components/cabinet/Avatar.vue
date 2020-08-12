<template>
  <div>
    <div
      v-if="imgSrc"
      class="d-flex flex-column align-items-center justify-content-center bordered"
    >
      <img ref="avatar" class="avatar-img" alt="Avatar" :src="imgSrc" />
      <button
        class="btn background-purple btn-secondary mt-2"
        @click="deleteClicked"
      >
        Удалить
      </button>
    </div>
    <div
      v-else
      class="d-flex flex-column align-items-center justify-content-center bordered"
    >
      <div v-if="!view" class="upload-photo-label">
        Нажмите, чтобы загрузить фото
      </div>
      <VueAvatar
        ref="vueavatar"
        :width="200"
        :height="200"
        :style="`opacity: ${view ? '1' : '0'}`"
        :rotation="rotation"
        :scale="scale"
        :color="[0, 0, 0, 0]"
        :border="0"
        @select-file="onSelectFile($event)"
      />
      <br />
      <label>
        <small>Масштаб: {{ scale }}x</small>
        <br />
        <input v-model="scale" type="range" min="1" max="3" step="0.02" />
      </label>
      <label>
        <small>Повернуть: {{ rotation }}°</small>
        <br />
        <input v-model="rotation" type="range" min="0" max="360" step="1" />
      </label>
      <br />
      <button
        class="btn background-purple btn-secondary mt-2"
        @click="saveClicked"
      >
        Загрузить
      </button>
    </div>
  </div>
</template>

<script>
import { VueAvatar } from 'vue-avatar-editor-improved';
import config from '@/config/config';
import { mapGetters } from 'vuex';

export default {
  name: 'Avatar',
  components: {
    VueAvatar,
  },
  middleware: ['auth'],
  props: {
    id: {
      type: String,
      required: true,
    },
    entity: {
      type: String,
      required: true,
    },
    behavior: {
      type: String,
      required: true,
    },
    imgSrc: {
      type: String,
      default: null,
    },
  },
  async fetch() {
    this.user = await {
      ...this.$auth.user,
    };
  },
  data: () => ({
    user: {},
    view: false,
    preview: '',
    rotation: 0,
    scale: 1,
  }),
  computed: mapGetters(['loggedInUser']),
  methods: {
    onSelectFile(file) {
      if (file[0]) {
        this.view = true;
      }
    },
    async saveClicked() {
      const canvas = document.getElementById('avatarEditorCanvas');
      canvas.toBlob(async (blob) => {
        let formData = new FormData();
        formData.append('image', blob);
        await this.$axios.post(
          `${config.api_url}/${this.$props.entity}/imgAttachApi?type=${this.$props.entity}&behavior=${this.$props.behavior}&id=${this.$props.id}`,
          formData
        );
        this.$auth.fetchUser();
      });
    },
    async deleteClicked() {
      await this.$axios.post(
        `${config.api_url}/${this.$props.entity}/imgAttachApi?type=${this.$props.entity}&behavior=${this.$props.behavior}&id=${this.$props.id}`,
        {
          remove: true,
          key: 'image',
        }
      );
      const canvas = document.createElement('canvas');
      canvas.style.opacity = 0;
      this.view = false;
      let ctx = canvas.getContext('2d');
      ctx.clearRect(1, 1, canvas.width - 2, canvas.height - 2);
      this.$auth.fetchUser();
    },
  },
};
</script>

<style lang="scss">
.bordered {
  border: 1px dashed #ddd;
  padding: 10px;
}

.cursorPointer {
  cursor: pointer;
  border-radius: 100%;
}

.upload-photo-label {
  position: relative;
  color: #767676;
  top: 70px;
  text-align: center;
}

.avatar-img {
  max-height: 200px;
  max-width: 200px;
  border-radius: 100%;
  height: 100%;
  width: 100%;
}

.avatar {
  position: absolute;
  background: transparent;
  top: 0;
  button {
    margin-left: 10px;
    margin-top: 40px;
    padding: 0.375rem 0.75rem;
    background-color: $purple;
    border-radius: 0.25rem;
    border: 1px solid #6c757d;
    color: $purple;
    font-size: 0;
    line-height: 1.5;
    &:before {
      content: 'Сохранить' !important;
      color: $white;
      font-size: 1rem;
    }
  }
  label {
    font-size: 1rem;
    margin-left: 12%;
  }
}
</style>
