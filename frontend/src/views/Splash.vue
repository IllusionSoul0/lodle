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
  <nav>
    <router-link to="/classic"><img src="/src/assets/icons/classic.png" alt="classic" width="50px" class="icone" /></router-link>
    <router-link to="/quote"><img src="/src/assets/icons/quotes.png" alt="quote" width="50px" class="icone" /></router-link>
    <router-link to="/splash"><img src="/src/assets/icons/splash.png" alt="classic" width="50px" class="icone" /></router-link>
  </nav>
  <div id="game" class="game-container">
    <div v-if="loading" class="status-message">Loading...</div>
    <div v-else-if="error" class="status-message error">Error: {{ error.message }}</div>

    <div v-else class="content-wrapper">
      <h1 class="game-title">Mode Splash Art</h1>
      <div class="splash-container">
        <img :src="answer.splash" alt="" style="object-fit: cover" :style="{ zoom: calculateZoom }" />
      </div>

      <div v-if="!found" class="search-section">
        <div class="search-box">
          <input v-model="guess" type="text" placeholder="Tape le nom d'un champion..." @keydown.enter="attempt" class="styled-input" />
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

      <div v-if="!found">
        <div class="button-container">
          <button @click="resetGame" class="btn-reset">Recommencer</button>
        </div>
      </div>

      <div v-else class="victory-section">
        <div class="victory-message">
          <h2>Félicitations !</h2>
          <p>
            Vous avez trouvé <strong>{{ answer.name }}</strong> en {{ attempts.length }} essais.
          </p>
        </div>
        <button @click="resetGame" class="btn-reset">Rejouer</button>
      </div>

      <ul class="attempts-list">
        <li
          v-for="champion in attempts.slice().reverse()"
          :key="champion.name"
          :class="['attempt-item', { correct: champion.name === answer.name, incorrect: champion.name !== answer.name }]"
        >
          <img :src="imgSrc(champion.name)" alt="" class="attempt-tile" />
          <span class="attempt-name">{{ champion.name }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<style>
.game-container {
  max-width: 1000px;
  margin: 20px auto;
  padding: 20px;
  background-color: rgba(0, 0, 0, 0.75);
  border-radius: 15px;
  color: #f0e6d2;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
}

.content-wrapper {
  width: 100%;
}

.game-title {
  text-align: center;
  font-size: 2.5rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 30px;
  color: #c89b3c;
}

.status-message {
  text-align: center;
  padding: 40px;
  font-size: 18px;
  color: #f0e6d2;
}

.status-message.error {
  color: #ffcccc;
  background-color: rgba(220, 53, 69, 0.2);
  border-radius: 4px;
  border: 1px solid #dc3545;
}

.splash-container {
  width: 600px;
  height: 350px;
  overflow: hidden;
  margin-bottom: 30px;
  border: 2px solid #c89b3c;
  border-radius: 8px;
  margin-left: auto;
  margin-right: auto;
}

.search-section {
  display: flex;
  justify-content: center;
  margin-bottom: 40px;
}

.search-box {
  position: relative;
  width: 100%;
  max-width: 500px;
  display: flex;
}

.styled-input {
  width: 100%;
  padding: 15px 20px;
  font-size: 1.1rem;
  background: #1e2328;
  border: 2px solid #c89b3c;
  color: #f0e6d2;
  border-radius: 5px 0 0 5px;
  outline: none;
}

.btn-send {
  background: #c89b3c;
  border: none;
  padding: 0 20px;
  cursor: pointer;
  border-radius: 0 5px 5px 0;
  transition: background 0.3s;
  font-weight: bold;
}

.btn-send:hover {
  background: #a07b30;
}

.arrow-circle {
  font-size: 1.5rem;
  color: #1e2328;
}

.suggestions-list {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #1e2328;
  border: 1px solid #c89b3c;
  z-index: 100;
  max-height: 250px;
  overflow-y: auto;
  list-style: none;
  padding: 0;
  margin-top: 5px;
}

.suggestion-item {
  display: flex;
  align-items: center;
  padding: 10px;
  cursor: pointer;
  border-bottom: 1px solid #32383e;
}

.suggestion-item:hover {
  background: #32383e;
}

.mini-tile {
  width: 40px;
  height: 40px;
  margin-right: 15px;
  border: 1px solid #c89b3c;
}

.suggestion-name {
  color: #f0e6d2;
}

.victory-section {
  text-align: center;
  margin-bottom: 30px;
}

.victory-message {
  background: rgba(40, 167, 69, 0.2);
  padding: 20px;
  border-radius: 10px;
  border: 2px solid #28a745;
  margin-bottom: 20px;
}

.victory-message h2 {
  color: #28a745;
  margin-bottom: 10px;
}

.button-container {
  display: flex;
  justify-content: center;
  margin-top: 30px;
  margin-bottom: 30px;
}

.btn-reset {
  background-color: transparent;
  color: #c89b3c;
  border: 2px solid #c89b3c;
  padding: 12px 35px;
  font-size: 1.1rem;
  font-weight: bold;
  cursor: pointer;
  border-radius: 4px;
  box-shadow: 0 0 10px rgba(200, 155, 60, 0.2);
  transition: all 0.3s ease;
}

.btn-reset:hover {
  background-color: #c89b3c;
  color: #1e2328;
  box-shadow: 0 0 20px rgba(200, 155, 60, 0.5);
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
  border-color: #1e7e34;
}

.attempt-item.incorrect {
  background-color: #dc3545;
  border-color: #bd2130;
}

.attempt-tile {
  width: 50px;
  height: 50px;
  border: 1px solid #c89b3c;
  margin-right: 20px;
  border-radius: 4px;
  object-fit: cover;
}

.attempt-name {
  font-size: 1.2rem;
  font-weight: bold;
  color: #f0e6d2;
  flex-grow: 1;
}

@media (max-width: 600px) {
  .game-title {
    font-size: 1.8rem;
  }
  .splash-container {
    width: 100%;
    height: 250px;
  }
  .attempt-item {
    padding: 8px 10px;
  }
  .attempt-tile {
    width: 40px;
    height: 40px;
  }
}
</style>
