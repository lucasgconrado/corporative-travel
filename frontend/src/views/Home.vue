<template>
  <BaseLayout>
    <div class="home">
      <div class="left">
        <h2 class="slide-in">Bem-vindo ao Corporate Travel</h2>
        <p class="slide-in delay">
          Uma nova solução para suas viagens corporativas. Aqui você pode personalizar esse texto para explicar melhor sua proposta, e ele ficará justificado ocupando o centro da metade esquerda da tela.
        </p>
      </div>
      <div class="right">
        <router-link to="/login" class="btn">Login</router-link>
        <router-link to="/register" class="btn">Cadastro</router-link>
        <router-link to="/orders" class="btn">Pedidos</router-link>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup lang="ts">
import BaseLayout from '../layouts/BaseLayout.vue';
</script>

<style scoped>
.home {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 70vh;
  padding: 40px;
  gap: 40px;
}

.left {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  max-width: 700px;
  margin: 100px;
}

.left h2,
.left p {
  text-align: justify;
}

.right {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 50px;
}

.btn {
  width: 260px;
  text-align: center;
  padding: 16px 0;
  background-color: var(--primary-color);
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
  transition: background-color 0.3s;
}

.btn:hover {
  background-color: var(--primary-dark);
}

.slide-in {
  opacity: 0;
  transform: translateX(-30px);
  animation: slideIn 1s forwards;
  font-size: 2.2em;
  font-weight: bold;
}

.slide-in.delay {
  animation-delay: 0.4s;
}

@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}


@media (max-width: 768px) {
  .home {
    flex-direction: column;
    gap: 40px;
    text-align: center;
  }

  .left {
    max-width: 90%;
    margin: 0 auto;
    text-align: justify;
  }

  .right {
    align-items: center;
    padding-right: 0;
  }

  .btn {
    width: 90%;
    max-width: 300px;
    padding: 14px 0;
  }

  .slide-in {
    font-size: 1.8em;
  }
}

</style>
