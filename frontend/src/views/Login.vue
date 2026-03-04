<template>
  <BaseLayout>
    <div class="login">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" type="email" v-model="email" placeholder="Digite seu email" />
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <input id="password" type="password" v-model="password" placeholder="Digite sua senha" />
        </div>

        <button type="submit">Entrar</button>
      </form>
    </div>
  </BaseLayout>
</template>

<script setup lang="ts">
import BaseLayout from '../layouts/BaseLayout.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const email = ref('')
const password = ref('')
const router = useRouter()

async function login() {
  try {
    const res = await api.post('/login', {
      email: email.value,
      password: password.value,
    })
    localStorage.setItem('token', res.data.token)
    localStorage.setItem('user', JSON.stringify(res.data.user))
    alert('Login efetuado com sucesso!')
    router.push('/orders')
  } catch (error) {
    alert('Login inválido')
  }
}

</script>

<style scoped>
.login {
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
