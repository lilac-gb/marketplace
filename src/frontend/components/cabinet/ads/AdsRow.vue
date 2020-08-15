<template>
  <div class="ad-row w-100 d-flex flex-row">
    <div class="image-cover">
      <img
        v-if="ad.coverImages"
        class="picture"
        :src="ad.coverImages.preview"
      />
    </div>
    <div class="text d-flex align-items-center">{{ ad.name }}</div>
    <div class="info d-flex align-items-center justify-content-end">
      <a href="#" @click.prevent="publicate">
        <b-icon
          :class="publishState.color"
          :icon="publishState.icon"
          v-b-tooltip.hover
          :title="publishState.text"
        ></b-icon>
      </a>
      <a href="#" @click.prevent="edit">
        <i class="fas fa-pencil-alt text-muted"></i>
      </a>
      <a href="#" @click.prevent="deleteP">
        <b-icon class="text-muted" icon="trash-fill"></b-icon>
      </a>
    </div>
  </div>
</template>

<script>
import { ModelStatuses } from '@/shared/constants';
import ads from '@/mixins/ads';

export default {
  name: 'AdsRow',
  mixins: [ads],
  props: {
    ad: { type: Object, required: true },
  },
  computed: {
    publishState() {
      switch (this.ad.status) {
        case(ModelStatuses.STATUS_MODERATION):
          return {
            color: 'text-yelow',
            icon: 'eye',
            text: 'Модерация',
          };
        case(ModelStatuses.STATUS_NOT_PUBLISHED):
          return {
            color: 'text-gray',
            icon: 'eye-slash',
            text: 'Редактирование',
          };
        case(ModelStatuses.STATUS_PUBLISHED):
          return {
            color: 'text-purple',
            icon: 'eye',
            text: 'Опубликовано',
          };
      }
    },
  },
  methods: {
    publicate() {
      if (this.ad.status === ModelStatuses.STATUS_NOT_PUBLISHED) {
        this.publishAd(this.ad.id);
      }
      this.$emit('updated');
    },
    deleteP() {
      this.deleteAd(this.ad.id);
      this.$emit('updated');
    },
    edit() {
      this.$router.push({
        name: 'cabinet-ads-ad_id',
        params: {
          ad_id: this.ad.id,
        },
      });
    },
  },
};
</script>

<style lang="scss">
.ad-row {
  height: 5.0625rem;
  background: white;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.25);
  border-radius: 5px;
  
  .image-cover {
    width: 110px;
    display: flex;
    align-items: center;
    overflow: hidden;
    border-radius: 5px 0 0 5px;
    justify-content: center;
    background: #f1f1f1;
    .picture {
      height: 100%;
    }
  }
  
  .text {
    flex: 1;
    height: 100%;
    padding: 0 22px 0 22px;
  }
  
  .info {
    height: 100%;
    padding: 0 28px 0 0;
    
    a {
      margin-left: 20px;
      
      i::before,
      .b-icon {
        
        height: 1.5625rem;
        width: 1.25rem;
        font-size: 1.25rem;
      }
    }
    
    a:first-of-type {
      margin-left: 0;
    }
  }
}
</style>
