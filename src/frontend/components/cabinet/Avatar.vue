<template>
  <div style="position: relative;">
    <img
      ref="avatar"
      class="avatar-img"
      alt="1"
      :src="loggedInUser.images.preview"
    />
    <VueAvatarEditor
      style=""
      :width="248"
      :height="248"
      :color="[206, 212, 218, 1]"
      :border="1"
      class="avatar"
      @finished="saveClicked"
    />
    <button
      class="btn background-purple btn-secondary delete"
      @click="deleteClicked"
    >
      Удалить
    </button>
  </div>
</template>

<script>
import VueAvatarEditor from 'vue-avatar-editor-improved';
import config from '@/config/config';
import { mapGetters } from 'vuex';
export default {
  name: 'Avatar',
  components: {
    VueAvatarEditor: VueAvatarEditor,
  },
  middleware: ['auth'],
  async fetch() {
    this.user = await {
      ...this.$auth.user,
    };
  },
  data: () => ({
    user: {},
    preview: '',
  }),
  computed: mapGetters(['loggedInUser']),
  methods: {
    async saveClicked() {
      const canvas = document.getElementById('avatarEditorCanvas');
      canvas.toBlob(async (blob) => {
        let formData = new FormData();
        formData.append('image', blob);
        await this.$axios.post(
          `${config.api_url}/user/imgAttachApi?type=user&behavior=avatarBehavior&id=${this.user.id}`,
          formData
        );
        this.$auth.fetchUser();
      });
    },
    async deleteClicked() {
      let formData = new FormData();
      formData.delete('image');
      await this.$axios.post(
        `${config.api_url}/user/imgAttachApi?type=user&behavior=avatarBehavior&id=${this.user.id}`,
        {
          remove: true,
          key: 'image',
        }
      );
      let canvas = document.getElementById('avatarEditorCanvas');
      let ctx = canvas.getContext('2d');
      ctx.clearRect(1, 1, canvas.width - 2, canvas.height - 2);
      this.$auth.fetchUser();
    },
  },
};
</script>

<style lang="scss">
.avatar-img {
  height: 250px;
  width: 250px;
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
.delete {
  position: absolute;
  margin-top: 190px;
  margin-left: 60%;
}
</style>
