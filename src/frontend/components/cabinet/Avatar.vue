<template>
  <div>
    <VueAvatarEditor
      :width="250"
      :height="250"
      :border="1"
      :color="[206, 212, 218, 1]"
      class="avatar"
      @finished="saveClicked"
    />
    <img ref="avatar" alt="1" />
  </div>
</template>

<script>
import VueAvatarEditor from 'vue-avatar-editor-improved';
import config from '@/config/config';
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
  }),
  methods: {
    saveClicked() {
      const canvas = document.getElementById("avatarEditorCanvas");
      console.log(canvas);
      canvas.toBlob((blob) => {
        let formData = new FormData();
        formData.append('image', blob);
        let response = this.$axios.post(
          `${config.api_url}/user/imgAttachApi?type=user&behavior=avatarBehavior&id=${this.user.id}`,
          formData
        );
        console.log(response)
      })
    },
  },
};
</script>

<style lang="scss">
.avatar {
  button {
    margin-left: 25%;
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
