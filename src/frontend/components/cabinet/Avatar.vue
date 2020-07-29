<template>
  <div class="text-center">
    <img v-if="userAvatar" :src="userAvatar" />
    <button id="pick-avatar">Select an image</button>
    <AvatarCropper
      trigger="#pick-avatar"
      upload-url="/files/upload"
      @uploaded="handleUploaded"
    />
  </div>
</template>

<script>
import AvatarCropper from 'vue-avatar-cropper';
import config from '@/config/config';

export default {
  name: 'Avatar',
  components: { AvatarCropper },
  async fetch() {
    try {
      this.user = await {
        ...this.$auth.user,
      };
      console.log(this.user);
    } catch (e) {
      console.log(e);
    }
  },
  data() {
    return {
      userAvatar: undefined,
    };
  },
  middleware: ['auth'],
  methods: {
    handleUploaded(resp) {
      this.userAvatar = resp.relative_url;
    },
  },
};
</script>

<style scoped></style>
