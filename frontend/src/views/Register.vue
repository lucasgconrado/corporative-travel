<template>
  <BaseLayout>
    <div class="register">
      <h2>Cadastro</h2>
      <form @submit.prevent="register">
        <div class="form-group">
          <label for="name">Nome</label>
          <input id="name" type="text" v-model="name" placeholder="Digite seu nome" />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" type="email" v-model="email" placeholder="Digite seu email" />
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <input id="password" type="password" v-model="password" placeholder="Digite sua senha" />
        </div>

        <label>
          <input type="checkbox" v-model="isAdmin" /> Admin
        </label>

        <button type="submit">Cadastrar</button>
      </form>
    </div>
  </BaseLayout>
</template>

<script setup lang="ts">
import BaseLayout from '../layouts/BaseLayout.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const name = ref('')
const email = ref('')
const password = ref('')
const isAdmin = ref(false)
const router = useRouter()

async function register() {
  try {
    await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      is_admin: isAdmin.value,
    })
    alert('Usuário criado com sucesso!')
    router.push('/login')
  } catch (error) {
    alert('Erro ao cadastrar')
  }
}
</script>

<style scoped>
.register {
  max-width: 400px;
  margin: 0 auto;
  text-align: center;
}

.form-group {
  margin-bottom: 15px;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background-color: var(--primary-dark);
}

</style>
