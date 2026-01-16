<script setup></script>

<template>
  <header>
    <nav>
      <router-link to="/login" id="link"><img src="/src/assets/icones/logo-login.png" alt="login" width="50px" class="icone">Connexion</router-link>
      <router-link to="/"><img src="/src/assets/logo/logo_loldle.png" alt="logo" width="500px"></router-link>
      <router-link to="/stats" id="link"><img src="/src/assets/icones/statistique.png" alt="stats" width="50px" class="icone">Stats</router-link>
  </nav>
  </header>
  
  <main>
    <router-view />
  </main>
</template>


<style>
  header, nav, main {
    display: flex;
    justify-content: center;
    align-items: center;
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