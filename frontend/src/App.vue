<script setup>
import { onMounted, ref, watch } from "vue";
import { isConnected, logout } from "./stores/auth";

const disconnect = () => {
  logout();
};

onMounted(() => {
  isConnected.value = localStorage.getItem("userId") !== null;
});
</script>

<template>
  <header>
    <nav>
      <router-link to="/login" id="link" @click="disconnect">{{ isConnected ? "Déconnexion" : "Connexion" }}</router-link>
      <router-link to="/"><img src="/src/assets/logo/logo_loldle.png" alt="logo" width="500px" /></router-link>
      <router-link to="/stats" id="link">Stats</router-link>
    </nav>
  </header>

  <main>
    <router-view />
  </main>
</template>

<style>
header,
nav,
main {
  display: flex;
  justify-content: center;
  align-items: center;
}

#link {
  margin: 0 15px;
  text-decoration: none;
  color: #333;
  background-color: antiquewhite;
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: bold;
}

#game {
  background-color: lightgray;
}
</style>
