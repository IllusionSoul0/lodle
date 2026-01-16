<script setup>
import { ref, onMounted, computed } from "vue";
import { imgSrc } from "@/utils/imgSrc.js";
import { useGameMode } from "@/components/useGameMode";

const { loading, error, champions, champions_list, attempts, answer, found, guess, suggestions, attempt, resetGame, init } = useGameMode("splash");

const calculateZoom = computed(() => {
  var zoom = 120 - attempts.value.length * 5;
  return found.value ? 50 + "%" : zoom < 75 ? 75 + "%" : zoom + "%";
});

// Fetch champions and initialize game state
onMounted(async () => {
  init();
});
</script>

<template>
  <div id="game">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>

    <div v-else style="padding: 15px">
      <h1>Mode Splash Art :</h1>
      <div class="splash-container">
        <img :src="answer.splash" alt="" style="object-fit: cover" :style="{ zoom: calculateZoom }" />
      </div>

      <div v-if="!found">
        <input v-model="guess" type="text" placeholder="Choisissez un champion..." @keydown.enter="attempt" />
        <button @click="attempt">Test</button>

        <ul v-if="suggestions.length" class="suggestions">
          <li v-for="name in suggestions" :key="name" @click="guess = name">
            <img :src="imgSrc(name)" alt="" />
            <span>{{ name }}</span>
          </li>
        </ul>
      </div>

      <div v-else>
        <h2>Félicitations ! Vous avez trouvé le champion {{ answer.name }} en {{ attempts.length }} essais.</h2>
      </div>

      <ul>
        <li v-for="champion in attempts.slice().reverse()" :key="champion.name" :style="{ backgroundColor: champion.name === answer.name ? 'green' : 'red' }" class="guess">
          <img :src="imgSrc(champion.name)" alt="" width="60px" /> {{ champion.name }}
        </li>
      </ul>

      <button @click="resetGame">Reset</button>
    </div>
  </div>
</template>

<style>
.guess {
  list-style: none;
  margin: 10px 0;
  padding: 5px;
  border: 1px solid #ccc;
  display: flex;
  align-items: center;
  gap: 10px;
}

.splash-container {
  width: 600px;
  height: 350px;
  overflow: hidden;
  margin-bottom: 15px;
  border: 2px solid #ccc;
  border-radius: 8px;
}
</style>
