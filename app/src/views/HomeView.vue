<template>
  <div class="container">
    <Header title="Bem vindo ao iClubs" description="Abaixo você pode visualizar seu gerenciamento" />
    <div class="home">
      <img alt="Vue logo" src="../assets/logo.png">
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
}
</style>
