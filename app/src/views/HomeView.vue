<template>
  <div class="container">
    <Header title="Bem vindo ao iClubs" />
    <div class="home">
      <h2>Selecione abaixo o que deseja gerenciars</h2>
      <div class="items">
        <div class="card" @click="$router.push('/clubs')">
          <img src="@/assets/shield.png" alt="Clubes">
          <h3>Clubes</h3>
        </div>
        <div class="card" @click="$router.push('/users')">
          <img src="@/assets/user.png" alt="Assinantes">
          <h3>Assinantes</h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Header from '../components/Header.vue';

export default {
  name: 'HomeView',
  components: {
    Header
  }
}
</script>

<style scoped lang="scss">
.container {
  width: 100%;
  max-width: 1120px;
  margin: 0 auto;
  animation: fadeIn 2s;

  .home {
    display: flex;
    flex-direction: column;

    h2 {
      color: var(--white);
      margin-bottom: 20px;
      font-weight: 400;
      margin-left: 15px;
    }

    .items {
      display: flex;
      justify-content: space-evenly;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;

      .card {
        background: linear-gradient(90deg, var(--green-dark) 0%, var(--green) 100%);
        border-radius: 8px;
        width: 100%;
        min-width: 320px;
        max-width: 450px;
        height: 180px;
        padding: 20px;
        cursor: pointer;

        position: relative;
        transition: filter 0.3s;

        &:hover {
          filter: brightness(0.9);
        }

        h3 {
          font-size: 36px;
          color: var(--white);
          font-weight: 400;
        }

        img {
          width: 150px;
          position: absolute;
          top: 15px;
          right: 0;
        }
      }
    }
  }
}

@keyframes fadeIn {
  0% {
    opacity: 0;
  }

  100% {
    opacity: 1;
  }
}
</style>
