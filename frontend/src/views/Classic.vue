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
  <div id="game">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>

    <div v-else style="padding: 15px">
      <h1>Mode classique :</h1>

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

      <table>
        <thead>
          <tr>
            <th>Champion</th>
            <th>Genre</th>
            <th>Rôle</th>
            <th>Espèce</th>
            <th>Ressource</th>
            <th>Type de portée</th>
            <th>Région(s)</th>
            <th>Année de sortie</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="champion in attempts.slice().reverse()">
            <td><img :src="imgSrc(champion.name)" alt="" width="60px" /></td>
            <td :style="{ backgroundColor: display(champion.gender, answer.gender).bg }">{{ display(champion.gender, answer.gender).text }}</td>
            <td :style="{ backgroundColor: display(champion.role, answer.role).bg }">{{ display(champion.role, answer.role).text }}</td>
            <td :style="{ backgroundColor: display(champion.species, answer.species).bg }">{{ display(champion.species, answer.species).text }}</td>
            <td :style="{ backgroundColor: display(champion.resource, answer.resource).bg }">{{ display(champion.resource, answer.resource).text }}</td>
            <td :style="{ backgroundColor: display(champion.range_type, answer.range_type).bg }">{{ display(champion.range_type, answer.range_type).text }}</td>
            <td :style="{ backgroundColor: display(champion.region, answer.region).bg }">{{ display(champion.region, answer.region).text }}</td>
            <td :style="{ backgroundColor: display(champion.release_year, answer.release_year).bg }">{{ display(champion.release_year, answer.release_year).text }}</td>
          </tr>
        </tbody>
      </table>

      <button @click="resetGame">Reset</button>
    </div>
  </div>
</template>
