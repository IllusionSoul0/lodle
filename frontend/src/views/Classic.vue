<script setup>
import { ref, onMounted, computed } from "vue";
import { API_URL } from "@/api";

const champions = ref([]);
const error = ref(null);
const loading = ref(true);
const answer = ref({});
const attempts = ref([]);
const champions_list = ref([]);
const guess = ref("");
const found = ref(false);

const imgSrc = (champion) => {
  const champion_name = champion
    .toLowerCase()
    .replace(/(?:^|[\s'])(\w)/g, (_, c) => c.toUpperCase())
    .replace(/[\s']/g, "");
  return `/champion/tiles/${champion_name}.png`;
};

const normalize = (value) => {
  if (Array.isArray(value)) {
    return value.map((v) => v.toString().replace(/[{}"]/g, "").trim());
  }
  return value.toString().trim();
};

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

const suggestions = computed(() => {
  if (!guess.value) return [];

  return champions_list.value.filter((name) => name.toLowerCase().startsWith(guess.value.toLowerCase()));
});

const attempt = () => {
  if (!suggestions.value.length) return;

  const exactMatch = suggestions.value.find((name) => name.toLowerCase() === guess.value.toLowerCase());

  const selected = exactMatch ?? suggestions.value[0];

  attempts.value.push(champions.value.find((c) => c.name.toLowerCase() === selected.toLowerCase()));

  if (selected.toLowerCase() === answer.value.name.toLowerCase()) {
    found.value = true;
  }
  champions_list.value = champions_list.value.filter((name) => name.toLowerCase() !== selected.toLowerCase());
  guess.value = "";
};

onMounted(async () => {
  try {
    const res = await fetch(`${API_URL}/champions`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    champions.value = json.data;
    champions_list.value = champions.value.map((c) => c.name);
    const answerIndex = Math.floor(Math.random() * champions.value.length);
    answer.value = champions.value[answerIndex];
  } catch (e) {
    error.value = e;
    console.error(e);
  } finally {
    loading.value = false;
  }
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

      <p>Champion à trouver : {{ answer.name }}</p>
    </div>
  </div>
</template>

<style>
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
</style>
