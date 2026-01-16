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
  <div id="game" class="game-container">
    <div v-if="loading" class="status-message">Loading...</div>
    <div v-else-if="error" class="status-message error">Error: {{ error.message }}</div>

    <div v-else class="content-wrapper">
      <h1 class="game-title">Mode Splash Art :</h1>
      <div class="splash-container">
        <img :src="answer.splash" alt="" style="object-fit: cover" :style="{ zoom: calculateZoom }" />
      </div>

      <div v-if="!found" class="search-section">
        <div class="search-box">
          <input 
            v-model="guess" 
            type="text" 
            placeholder="Tape le nom d'un champion..." 
            @keydown.enter="attempt"
            class="styled-input"
          />
          <button @click="attempt" class="btn-send">
            <span class="arrow-circle">➤</span>
          </button>
  
          <ul v-if="suggestions.length" class="suggestions-list">
            <li v-for="name in suggestions" :key="name" @click="guess = name" class="suggestion-item">
              <img :src="imgSrc(name)" alt="" class="mini-tile" />
              <span class="suggestion-name">{{ name }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div v-else class="victory-message">
        <h2>Félicitations ! Vous avez trouvé le champion {{ answer.name }} en {{ attempts.length }} essais.</h2>
      </div>

      <button @click="resetGame" class="btn-reset">Recommencer</button>

      <ul>
        <li v-for="champion in attempts.slice().reverse()" :key="champion.name" :style="{ backgroundColor: champion.name === answer.name ? 'green' : 'red' }" class="guess">
          <img :src="imgSrc(champion.name)" alt="" width="60px" /> {{ champion.name }}
        </li>
      </ul>

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
  border: 2px solid #c89b3c;
  border-radius: 8px;
}
</style>
