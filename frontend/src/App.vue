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
      <router-link to="/login" id="link"><img src="/src/assets/icones/logo-login.png" alt="login" width="50px" class="icone" />Connexion</router-link>
      <router-link to="/"><img src="/src/assets/logo/logo_loldle.png" alt="logo" width="500px" /></router-link>
      <router-link to="/stats" id="link"><img src="/src/assets/icones/statistique.png" alt="stats" width="50px" class="icone" />Stats</router-link>
    </nav>
  </header>

  <main>
    <router-view />
  </main>

  <footer>Maxime CHAINTREAU, Nikola TODOROVIC, Thibaut MINARD - 2026</footer>
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

footer {
  text-align: center;
  margin: 20px 0;
  font-size: 14px;
  color: #fff;
}

.suggestions {
  list-style: none;
  margin: 0;
  padding: 0;
  border: 1px solid #ccc;
  max-height: 150px;
  overflow-y: auto;
}

.suggestions li {
  padding: 2px 10px;
  cursor: pointer;
}

.suggestions li:hover {
  background: #eee;
}

.suggestions img {
  width: 30px;
  height: 30px;
  margin-right: 10px;
  vertical-align: middle;
}

td {
  border: 2px solid black;
  width: 25px;
  padding: 0;
}

#link {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0 15px;
  text-decoration: none;
  color: #ffffff;
  /* background-color: antiquewhite; */
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: bold;
}

#game {
  background-color: lightgray;
  padding: 20px;
  border-radius: 5px;
}

.icone {
  margin: 10px;
  transition: transform 0.2s;
  width: 50px;
  height: auto;
  cursor: pointer;
}
.icone:hover {
  transform: scale(1.1);
}
</style>
