<template>
  <div class="layout">
    <header>
      <router-link to="/" class="logo">
        <img src="/logo.png" alt="Corporate Travel Logo" />
      </router-link>
      <h1 class="title">Corporate Travel</h1>
      <button class="menu-toggle" @click="toggleMenu">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </header>

    <div v-if="menuOpen" class="menu-modal">
      <router-link to="/login" @click="toggleMenu">Login</router-link>
      <router-link to="/register" @click="toggleMenu">Cadastro</router-link>
      <router-link to="/orders" @click="toggleMenu">Pedidos</router-link>

      <router-link to="/" @click="logout">Logout</router-link>
    </div>

    <main>
      <slot />
    </main>

    <footer>
      <p>&copy; 2026 Corporate Travel</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import api from '../services/api'

const menuOpen = ref(false)

function toggleMenu() {
  menuOpen.value = !menuOpen.value
}

async function logout() {
  try {
    await api.post('/logout')
  } catch (e) {
    console.error('Erro ao fazer logout', e)
  }
  localStorage.removeItem('token')
}
</script>


<style scoped>
.layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: 'Roboto', sans-serif;
}

header {
  background-color: var(--primary-color);
  color: white;
  padding: 15px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo img {
  height: 40px;
  cursor: pointer;
}

.title {
  flex: 1;
  text-align: center;
  font-size: 1.5em;
}

.menu-toggle {
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.menu-toggle span {
  width: 25px;
  height: 3px;
  background: white;
  border-radius: 2px;
}

.menu-modal {
  position: absolute;
  top: 60px;
  right: 20px;
  background: var(--primary-dark);
  border-radius: 8px;
  padding: 15px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  z-index: 1000;
}

.menu-modal a {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.menu-modal a:hover {
  color: var(--gray-light);
}



.mobile-menu {
  background-color: var(--primary-dark);
  display: flex;
  flex-direction: column;
  padding: 10px;
}

.mobile-menu a {
  color: white;
  text-decoration: none;
  padding: 8px 0;
}

main {
  flex: 1;
  padding: 20px;
}

footer {
  background-color: var(--gray-light);
  text-align: center;
  padding: 10px;
  font-size: 0.9em;
  margin-top: auto;
  position: relative;
}

</style>
