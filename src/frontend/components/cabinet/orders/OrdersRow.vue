<template>
  <div class="order-row w-100" v-if="order">
    <div class="d-flex flex-row align-items-center">
      <div class="text d-flex align-items-center">
        Заказ №{{ order.id }} - <span :class="`ml-2 ${statusState.color}`">{{statusState.text }}</span>
        <div class="icon create-at ml-3">
          от {{ timestampToDate(order.created_at, true) }}
        </div>
      </div>
      <div class="info d-flex align-items-center justify-content-end">
        <a href="#" @click.prevent="openInfo(order.id)">
          <i class="fas fa-info text-muted"></i>
        </a>
        <a href="#" @click.prevent="handleDeleteClick(order.id)">
          <b-icon class="text-muted" icon="trash-fill"></b-icon>
        </a>
      </div>
    </div>
    <div v-if="order.id === this.openId" class="px-4 py-2">
      <h4 class="mt-1 mb-3 text-muted">Подробности заказа</h4>
      <div class="text-muted mb-1">ФИО: {{ order.name }}</div>
      <div class="text-muted mb-1">Адрес: {{ order.address }}</div>
      <div class="text-muted mb-1">Телефон: {{ order.phone }}</div>
      <div class="text-muted mb-1">Сообщение: {{ order.text }}</div>
      <div class="mt-2">
         <b-table :items="order.items" responsive="sm" :fields="fields"></b-table>
      </div>
    </div>
  </div>
</template>

<script>
import { ModelStatuses } from '@/shared/constants';
import orders from '@/mixins/orders';
import utils from '@/mixins/utils';

export default {
  name: 'OrdersRow',
  mixins: [orders, utils],
  props: {
    order: { type: Object, required: true },
  },
  data: () => ({
    openId: 0,
    fields: [
      {
        key: 'ad_name',
        label: 'Название',
        sortable: true,
      },
      {
        key: 'count',
        label: 'Кол-во',
        sortable: true,
      },
      {
        key: 'price',
        label: 'Стоимость/шт',
        sortable: true,
      },
      {
        key: 'total',
        label: 'ИТОГО',
        sortable: true,
        formatter: (value, key, item) => {
          return item.count * item.price;
        },
      },
    ],
  }),
  computed: {
    statusState() {
      switch (this.order.status) {
        case(ModelStatuses.STATUS_SHIPPING):
          return {
            color: 'text-yelow',
            icon: 'eye',
            text: 'Доставка',
          };
        case(ModelStatuses.STATUS_PROCESS):
          return {
            color: 'text-gray',
            icon: 'eye-slash',
            text: 'Оформление',
          };
        case(ModelStatuses.STATUS_DONE):
          return {
            color: 'text-purple',
            icon: 'eye',
            text: 'Выполнено',
          };
      }
    },
  },
  methods: {
    handleDeleteClick(id) {
      this.$confirm(
        {
          message: `Вы точно хотите удалить заказ "${id}"`,
          button: {
            no: 'Нет',
            yes: 'Да',
          },
          
          callback: confirm => {
            if (confirm) {
              this.deleteP();
            }
          },
        },
      );
    },
    deleteP() {
      this.deleteOrder(this.order.id);
      this.$emit('updated');
    },
    openInfo(id) {
      this.openId = (this.openId === id ? 0 : id);
    },
  },
};
</script>

<style lang="scss">
.order-row {
  padding: 15px 10px;
  background: white;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.25);
  border-radius: 5px;
  
  .image-cover {
    width: 110px;
    display: flex;
    align-items: center;
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
