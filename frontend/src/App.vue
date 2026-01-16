<script setup>
import { onMounted, ref, watch } from "vue";
import { isConnected, logout } from "./stores/auth";

const disconnect = () => {
  if (isConnected.value) {
    logout();
  }
};

onMounted(() => {
  isConnected.value = localStorage.getItem("userId") !== null;
});
</script>

<template>
  <header>
    <nav>
      <router-link to="/login" id="link" @click="disconnect"
        ><img src="/src/assets/icons/logo-login.png" alt="login" width="50px" class="icone" />{{ isConnected ? "Déconnexion" : "Connexion" }}
      </router-link>
      <router-link to="/"><img src="/src/assets/logo/logo_loldle.png" alt="logo" id="logo" /></router-link>
      <router-link to="/stats" id="link"><img src="/src/assets/icons/statistique.png" alt="stats" width="50px" class="icone" />Stats</router-link>
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

main {
  flex-direction: column;
}

html {
  scrollbar-width: none;
}

#link {
  margin: 0 15px;
  text-decoration: none;
  color: #c89b3c;
  background-color: rgba(0, 0, 0, 0.35);
  padding: 10px 20px;
  border-radius: 5px;
  width: 10vw;
  border: 2px solid #c89b3c;
  font-weight: bold;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: all 0.3s ease;
}

#logo {
  width: 40vw;
}

.game-container {
  width: 60vw;
}

#link:hover {
  background-color: #c89b3c;
  color: #1e2328;
}

footer {
  text-align: center;
  margin: 20px 0;
  font-size: 14px;
  color: #a09b8c;
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

@media (max-width: 1024px) {
  #logo {
    width: 50vw;
  }

  .game-container {
    width: 80vw;
  }

  #link {
    width: auto;
    margin: 0 10px;
    padding: 8px 15px;
    font-size: 12px;
  }
}

@media (max-width: 768px) {
  nav {
    flex-wrap: wrap;
  }

  #logo {
    width: 70vw;
    order: -1;
  }

  #link {
    width: auto;
    margin: 5px;
    padding: 6px 12px;
    font-size: 11px;
  }

  .icone {
    width: 40px;
    margin: 5px;
  }

  .game-container {
    width: 90vw;
  }

  footer {
    font-size: 12px;
    margin: 15px 0;
  }
}

@media (max-width: 480px) {
  header {
    padding: 10px;
  }

  nav {
    flex-direction: column;
    gap: 5px;
  }

  #logo {
    width: 80vw;
    margin-bottom: 10px;
  }

  #link {
    width: 100%;
    margin: 3px 0;
    padding: 8px;
    font-size: 10px;
  }

  .icone {
    width: 30px;
    margin: 3px;
  }

  .game-container {
    width: 95vw;
    margin: 10px auto;
    padding: 10px;
  }

  footer {
    font-size: 10px;
    margin: 10px 0;
  }
}
</style>
