<template>
  <div class="order-card">
    <p><strong>Pedido #{{ order.id }}</strong> - {{ order.description }}</p>
    <div class="actions">
      <button class="approve" @click="$emit('approve', order.id)">Aprovar</button>
      <button class="cancel" @click="$emit('cancel', order.id)">Cancelar</button>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  order: {
    id: number;
    description: string;
  };
}>();

defineEmits<{
  (e: 'approve', id: number): void;
  (e: 'cancel', id: number): void;
}>();
</script>

<style scoped>
.order-card {
  background: #f5f5f5;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 5px;
}

.actions {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}

button {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
}

.approve {
  background-color: #4caf50;
  color: white;
}

.approve:hover {
  background-color: #43a047;
}

.cancel {
  background-color: #e53935;
  color: white;
}

.cancel:hover {
  background-color: #c62828;
}
</style>
