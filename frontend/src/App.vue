<script setup></script>

<template>
  <header>
      <router-link to="/"><img src="/src/assets/logo/logo_loldle.png" alt="logo"></router-link>
  </header>

  <nav>
    <router-link to="/">Home</router-link>
    <router-link to="/classic">Classic</router-link>
    <router-link to="/login">Login</router-link>
    <router-link to="/stats">Stats</router-link>
  </nav>
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

  nav a {
    margin: 0 15px;
    text-decoration: none;
    color: #333;
    background-color: antiquewhite;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
  }

  main {
    margin-top: 20px;
    background-color: beige;
    padding: 20px;
    border-radius: 10px;
    min-height: 400px;
    width: 60%;
    justify-self: center;
  }
</style>