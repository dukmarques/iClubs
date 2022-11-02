<template>
  <Navbar v-show="this.$route.path != '/login'" />
  <router-view />
</template>

<script>
import Navbar from './components/Navbar.vue';

export default {
  components: {
    Navbar
  }
}
</script>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --white: #FFFFFF;

  --green: #058654;
  --green-light: #00d581;
  --green-dark: #10393B;

  --orange: #ffc107;

  --red: #E52E4D;

  --gray: #C7C7C7;
  -gray-50: #969CB3;

  --black: #1e1e1e;
  --black-50: #363F5F;
}

@media (max-width: 1080px) {
  html {
    font-size: 93.75%;
  }
}

@media (max-width: 720px) {
  html {
    font-size: 87.5%;
  }
}

body {
  background: var(--black);
}

body,
input,
textarea,
select,
button {
  font: 400 1rem "Poppins", sans-serif;
}

button {
  cursor: pointer;
}

a {
  color: inherit;
  text-decoration: none;
}
</style>
