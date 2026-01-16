<script setup>
import { ref, onMounted, computed } from "vue";
import { imgSrc } from "@/utils/imgSrc.js";
import { useGameMode } from "@/components/useGameMode";

const { loading, error, champions, champions_list, attempts, answer, found, guess, suggestions, attempt, resetGame, init } = useGameMode("classic");

// Normalize values for display
const normalize = (value) => {
  if (Array.isArray(value)) {
    return value.map((v) => v.toString().replace(/[{}"]/g, "").trim());
  }
  return value.toString().trim();
};

// Manage the color of the display based on guess and answer
const display = (guessValue, answerValue) => {
  const guessArr = Array.isArray(guessValue) ? normalize(guessValue) : [normalize(guessValue)];
  const answerArr = Array.isArray(answerValue) ? normalize(answerValue) : [normalize(answerValue)];

  if (guessArr.length === answerArr.length && guessArr.every((v, i) => v.toLowerCase() === answerArr[i].toLowerCase())) {
    return { text: guessArr.join(", "), bg: "green" };
  }

  if (guessArr.some((g) => answerArr.some((a) => a.toLowerCase() === g.toLowerCase()))) {
    return { text: guessArr.join(", "), bg: "orange" };
  }

  return { text: guessArr.join(", "), bg: "red" };
};

// Fetch champions and initialize game state
onMounted(async () => {
  init();
});
</script>

<template>
  <div id="game" class="game-container">
    <div v-if="loading" class="status-message">Chargement des champions...</div>
    <div v-else-if="error" class="status-message error">Erreur: {{ error.message }}</div>

    <div v-else class="content-wrapper">
      <h1 class="game-title">Mode classique</h1>

      <div v-if="!found" class="search-section">
        <div class="search-box">
          <input v-model="guess" type="text" placeholder="Tapez le nom d'un champion..." @keydown.enter="attempt" class="styled-input" />
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

      <div v-else class="victory-message">
        <h2>Félicitations !</h2>
        <p>
          Vous avez trouvé <strong>{{ answer.name }}</strong> en {{ attempts.length }} essais.
        </p>
      </div>

      <div class="table-responsive">
        <table class="classic-table">
          <thead>
            <tr>
              <th>Champion</th>
              <th>Genre</th>
              <th>Rôle</th>
              <th>Espèce</th>
              <th>Ressource</th>
              <th>Type de Portée</th>
              <th>Région</th>
              <th>Année de Sortie</th>
            </tr>
          </thead>
          <transition-group name="list" tag="tbody">
            <tr v-for="champion in attempts.slice().reverse()" :key="champion.name" class="guess-row">
              <td class="cell-img">
                <div class="cell-content">
                  <img :src="imgSrc(champion.name)" alt="" />
                </div>
              </td>
              <td :class="['cell', display(champion.gender, answer.gender).bg]">
                <div class="cell-content">{{ display(champion.gender, answer.gender).text }}</div>
              </td>
              <td :class="['cell', display(champion.role, answer.role).bg]">
                <div class="cell-content">{{ display(champion.role, answer.role).text }}</div>
              </td>
              <td :class="['cell', display(champion.species, answer.species).bg]">
                <div class="cell-content">{{ display(champion.species, answer.species).text }}</div>
              </td>
              <td :class="['cell', display(champion.resource, answer.resource).bg]">
                <div class="cell-content">{{ display(champion.resource, answer.resource).text }}</div>
              </td>
              <td :class="['cell', display(champion.range_type, answer.range_type).bg]">
                <div class="cell-content">{{ display(champion.range_type, answer.range_type).text }}</div>
              </td>
              <td :class="['cell', display(champion.region, answer.region).bg]">
                <div class="cell-content">{{ display(champion.region, answer.region).text }}</div>
              </td>
              <td :class="['cell', display(champion.release_year, answer.release_year).bg]">
                <div class="cell-content">
                  {{ display(champion.release_year, answer.release_year).text }}
                  <span>{{ champion.release_year > answer.release_year ? "⬇️" : champion.release_year < answer.release_year ? "⬆️" : "" }}</span>
                </div>
              </td>
            </tr>
          </transition-group>
        </table>
      </div>

      <button @click="resetGame">Reset</button>
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
  color: white;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
}

.game-title {
  text-align: center;
  font-size: 2.5rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 30px;
  color: #c89b3c;
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
}

.btn-send:hover {
  background: #a07b30;
}

.arrow {
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

.table-responsive {
  overflow-x: auto;
  margin-top: 20px;
}

.classic-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 8px;
  min-width: 800px;
}

th {
  padding-bottom: 10px;
  font-size: 0.9rem;
  text-transform: uppercase;
  color: #a09b8c;
  border-bottom: 2px solid #5b5a56;
}

.cell {
  width: 100px;
  height: 100px;
  text-align: center;
  vertical-align: middle;
  border: 2px solid #5b5a56;
  font-weight: bold;
  position: relative;
  overflow: hidden;
  transition: all 0.5s ease;
}

.cell-content {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 5px;
  word-break: break-word;
}

.cell-img {
  width: 80px;
  border: 2px solid #c89b3c;
  background: black;
}

.cell-img img {
  width: 100%;
  display: block;
}

.green {
  background-color: #28a745;
  border-color: #1e7e34;
}
.orange {
  background-color: #ff8c00;
  border-color: #d47500;
}
.red {
  background-color: #dc3545;
  border-color: #bd2130;
}

.victory-message {
  text-align: center;
  background: rgba(40, 167, 69, 0.2);
  padding: 20px;
  border-radius: 10px;
  border: 2px solid #28a745;
  margin-bottom: 30px;
}

.hint {
  text-align: center;
  font-style: italic;
  color: #5b5a56;
  margin-top: 20px;
}

.list-enter-active {
  transition: all 0.6s ease;
}
.list-enter-from {
  opacity: 0;
  transform: translateY(-30px);
}

@media (max-width: 600px) {
  .game-title {
    font-size: 1.8rem;
  }
  .cell {
    width: 70px;
    height: 70px;
    font-size: 0.8rem;
  }
  .table-responsive {
    mask-image: linear-gradient(to right, black 85%, transparent 100%);
  }
}
</style>
