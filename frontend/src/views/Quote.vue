<script setup>
import { ref, onMounted, computed } from "vue";
import { imgSrc } from "@/utils/imgSrc.js";
import { useGameMode } from "@/components/useGameMode";

const { loading, error, champions, champions_list, attempts, answer, found, guess, suggestions, attempt, resetGame, init } = useGameMode("quote");

// Fetch champions and initialize game state
onMounted(async () => {
  init();
});
</script>

<template>
  <div id="game" class="game-container">
    <div v-if="loading" class="status-message">Chargement...</div>
    <div v-else-if="error" class="status-message error">Erreur: {{ error.message }}</div>

    <div v-else class="content-wrapper">
      <h1 class="game-title">Mode Citation</h1>

      <div class="quote-card">
        <p class="quote-header">Quel champion dit</p>
        <div class="quote-content">
          <span class="quote-mark left">“</span>
          <p class="quote-text">{{ answer.quote }}</p>
          <span class="quote-mark right">”</span>
        </div>
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
            <span class="arrow">➤</span>
          </button>
  
          <ul v-if="suggestions.length" class="suggestions-list">
            <li v-for="name in suggestions" :key="name" @click="guess = name" class="suggestion-item">
              <img :src="imgSrc(name)" alt="" class="mini-tile" />
              <span class="suggestion-name">{{ name }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div v-else class="victory-section">
        <div class="victory-message">
          <h2>Félicitations !</h2>
          <p>C'était bien <strong>{{ answer.name }}</strong>.</p>
        </div>
        <button @click="resetGame" class="btn-reset">Rejouer</button>
      </div>

      <div class="attempts-container">
        <transition-group name="list" tag="ul" class="attempts-list">
          <li v-for="champion in attempts.slice().reverse()" 
              :key="champion.name" 
              class="attempt-item"
              :class="{ 'correct': champion.name === answer.name, 'incorrect': champion.name !== answer.name }">
            <img :src="imgSrc(champion.name)" alt="" class="attempt-tile" />
            <span class="attempt-name">{{ champion.name }}</span>
            
          </li>
        </transition-group>
      </div>
    </div>
  </div>
</template>

<style>
.quote-card {
background-color: #1e2328;
border: 2px solid #c89b3c;
border-radius: 8px;
padding: 30px;
margin-bottom: 30px;
position: relative;
text-align: center;
}

.quote-header {
color: #f0e6d2;
font-size: 1.2rem;
margin-bottom: 15px;
font-family: 'Beaufort for LoL', serif;
}

.quote-content {
display: flex;
align-items: center;
justify-content: center;
gap: 15px;
}

.quote-text {
font-size: 1.8rem;
font-weight: bold;
color: white;
line-height: 1.2;
max-width: 80%;
}

.quote-mark {
font-size: 4rem;
color: #c89b3c;
font-family: serif;
opacity: 0.8;
line-height: 0;
}

.attempts-list {
  list-style: none;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.attempt-item {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  border-radius: 5px;
  border: 2px solid #5b5a56;
}

.attempt-item.correct { 
  background-color: #28a745; 
  border-color: #28a745; 
}
.attempt-item.incorrect { 
  background-color: #dc3545; 
  border-color: #dc3545; 
}

.attempt-tile { 
  width: 50px; 
  height: 50px; 
  border: 1px solid #c89b3c; 
  margin-right: 20px; 
}

.attempt-name { 
  font-size: 1.2rem; 
  font-weight: bold; 
  flex-grow: 1; 
}
.status-icon { 
  font-size: 1.5rem; 
  }
</style>
