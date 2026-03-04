<template>
  <BaseLayout>
    <div class="orders">
      <h2>Meus Pedidos</h2>

      <form @submit.prevent="createOrder" class="order-form">
        <input v-model="solicitante" placeholder="Solicitante" />

        <select v-model="destino">
          <option disabled value="">Selecione o destino</option>
          <option value="São Paulo">São Paulo</option>
          <option value="Rio de Janeiro">Rio de Janeiro</option>
          <option value="Belo Horizonte">Belo Horizonte</option>
          <option value="Curitiba">Curitiba</option>
        </select>

        <input type="date" v-model="dataIda" />
        <input type="date" v-model="dataVolta" />

        <button type="submit">Criar Pedido</button>
      </form>

      <div class="filters">
        <h3 class="filters-title">Filtros</h3>

        <div class="filters-grid top-filters">
          <div class="filter-item">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter" v-model="statusFilter" @change="fetchOrders">
              <option value="">Todos</option>
              <option value="solicitado">Solicitado</option>
              <option value="aprovado">Aprovado</option>
              <option value="cancelado">Cancelado</option>
            </select>
          </div>

          <div class="filter-item">
            <label for="destinoFilter">Destino:</label>
            <select id="destinoFilter" v-model="destinoFilter" @change="fetchOrders">
              <option value="">Todos</option>
              <option value="São Paulo">São Paulo</option>
              <option value="Rio de Janeiro">Rio de Janeiro</option>
              <option value="Belo Horizonte">Belo Horizonte</option>
              <option value="Curitiba">Curitiba</option>
            </select>
          </div>
        </div>

        <div class="filter-item period">
          <label>Período de viagem:</label>
          <div class="period-inputs">
            <input type="date" v-model="dataInicio" @change="fetchOrders" />
            <span class="period-separator">→</span>
            <input type="date" v-model="dataFim" @change="fetchOrders" />
          </div>
        </div>
      </div>

      <ul class="orders-list">
        <li v-for="order in orders" :key="order.id" class="order-card">
          <div class="order-info">
            <div class="order-destino">
              <strong>{{ order.destino }}</strong>
              <span class="order-dates">
                ({{ formatDateForDisplay(order.data_ida) }} → {{ formatDateForDisplay(order.data_volta) }})
              </span>
            </div>
            <div class="order-meta">
              <span class="status-badge" :class="order.status">{{ order.status }}</span>
              <span class="order-solicitante">Solicitante: {{ order.solicitante }}</span>
            </div>
          </div>

          <div class="order-actions" v-if="isAdmin && order.status === 'solicitado'">
            <button class="approve" @click="approve(order.id)">Aprovar</button>
            <button class="cancel" @click="cancel(order.id)">Cancelar</button>
            <button class="edit" @click="editOrder(order)">Editar</button>
          </div>
        </li>
      </ul>

      <div v-if="editingOrder" class="edit-modal">
        <h3>Editar Pedido</h3>
        <form @submit.prevent="updateOrder">
          <input v-model="editingOrder.solicitante" placeholder="Solicitante" />
          <select v-model="editingOrder.destino">
            <option value="São Paulo">São Paulo</option>
            <option value="Rio de Janeiro">Rio de Janeiro</option>
            <option value="Belo Horizonte">Belo Horizonte</option>
            <option value="Curitiba">Curitiba</option>
          </select>
          <input type="date" v-model="editingOrder.data_ida" />
          <input type="date" v-model="editingOrder.data_volta" />
          <button type="submit">Salvar</button>
          <button type="button" @click="editingOrder = null">Cancelar</button>
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '../services/api'
import BaseLayout from '../layouts/BaseLayout.vue'

const orders = ref<any[]>([])
const solicitante = ref('')
const destino = ref('')
const dataIda = ref('')
const dataVolta = ref('')
const editingOrder = ref<any | null>(null)
const isAdmin = ref(false)
const statusFilter = ref('')
const destinoFilter = ref('') 
const dataInicio = ref('') 
const dataFim = ref('')


onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  isAdmin.value = user.is_admin === true || user.is_admin === 1 || user.is_admin === "1"
  fetchOrders()
})

async function fetchOrders() {
  const params: any = {}

  if (statusFilter.value) {
    params.status = statusFilter.value
  }
  if (destinoFilter.value) {
    params.destino = destinoFilter.value
  }
  if (dataInicio.value && dataFim.value) {
    params.data_inicio = dataInicio.value
    params.data_fim = dataFim.value
  }

  const response = await api.get('/orders', { params })
  orders.value = response.data
}

function formatDateForBackend(date: string) {
  if (!date) return ''
  const [year, month, day] = date.split('-')
  return `${year}-${month}-${day}` 
}

function formatDateForDisplay(date: string) {
  if (!date) return ''
  const [year, month, day] = date.split('-')
  return `${day}/${month}/${year}`
}

async function createOrder() {
  try {
    await api.post('/orders', {
      solicitante: solicitante.value,
      destino: destino.value,
      data_ida: formatDateForBackend(dataIda.value), 
      data_volta: formatDateForBackend(dataVolta.value),
    })
    alert('Pedido criado com sucesso!')
    solicitante.value = ''
    destino.value = ''
    dataIda.value = ''
    dataVolta.value = ''
    fetchOrders()
  } catch (e) {
    alert('Erro ao criar pedido')
  }
}

async function approve(id: number) {
  try {
    await api.put(`/orders/${id}/approve`)
    alert('Pedido aprovado!')
    fetchOrders()
  } catch (e) {
    alert('Erro ao aprovar pedido')
  }
}

async function cancel(id: number) {
  try {
    await api.put(`/orders/${id}/cancel`)
    alert('Pedido cancelado!')
    fetchOrders()
  } catch (e) {
    alert('Erro ao cancelar pedido')
  }
}

function editOrder(order: any) {
  editingOrder.value = { ...order }
}

async function updateOrder() {
  try {
    await api.put(`/orders/${editingOrder.value.id}`, {
      solicitante: editingOrder.value.solicitante,
      destino: editingOrder.value.destino,
      data_ida: editingOrder.value.data_ida,
      data_volta: editingOrder.value.data_volta,
    })
    alert('Pedido atualizado!')
    editingOrder.value = null
    fetchOrders()
  } catch (e) {
    alert('Erro ao atualizar pedido')
  }
}

</script>

<style scoped>

.orders {
  max-width: 700px;
  margin: 0 auto;
  padding: 20px;
}

/* Formulário de criação */
.order-form {
  margin-bottom: 20px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.order-form input,
.order-form select {
  flex: 1;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.order-form button {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  background-color: #007bff;
  color: #fff;
  cursor: pointer;
}

.order-form button:hover {
  background-color: #0056b3;
}

/* Lista de pedidos */
.orders-list {
  list-style: none;
  padding: 0;
}

.orders-list li {
  background: #fff;
  margin-bottom: 15px;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Informações do pedido */
.order-info {
  width: 100%;
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
}

.order-destino {
  font-size: 1.1em;
}

.order-dates {
  margin-left: 6px;
  color: #555;
  font-size: 0.9em;
}

.order-meta {
  text-align: right;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

/* Badge de status */
.status-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 90px;
  text-align: center;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.85em;
  font-weight: bold;
  color: #fff;
  text-transform: capitalize;
}


.status-badge.solicitado {
  background-color: #007bff;
}

.status-badge.aprovado {
  background-color: #28a745;
}

.status-badge.cancelado {
  background-color: #dc3545;
}

/* Filtros */
.filters {
  margin-bottom: 20px;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.filters-title {
  margin-bottom: 12px;
  font-size: 1.2em;
  font-weight: bold;
  color: #333;
}

.top-filters {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 15px;
  margin-bottom: 15px;
}

.filter-item {
  display: flex;
  flex-direction: column;
}

.filter-item label {
  margin-bottom: 6px;
  font-size: 0.9em;
  color: #555;
}

.filter-item select,
.filter-item input[type="date"] {
  padding: 6px 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Período */
.period-inputs {
  display: flex;
  align-items: center;
  gap: 8px;
}

.period-separator {
  font-weight: bold;
  color: #333;
}


/* Botões de ação */
.order-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 10px;
}

.order-actions button {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: #fff;
}

.order-actions .approve {
  background-color: #28a745;
}

.order-actions .cancel {
  background-color: #dc3545;
}

.order-actions .edit {
  background-color: #007bff;
}

/* Modal de edição */
.edit-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  width: 400px;
  max-width: 90%;
  z-index: 1000;
}

.edit-modal h3 {
  margin-bottom: 15px;
}

.edit-modal form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.edit-modal input,
.edit-modal select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.edit-modal button {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: #fff;
}

.edit-modal button[type="submit"] {
  background-color: #007bff;
}

.edit-modal button[type="button"] {
  background-color: #6c757d;
}


</style>
