<script setup>
import { ref, onMounted, computed } from "vue";
import { API_URL } from "@/components/api";

const champions = ref([]);
const error = ref(null);
const loading = ref(true);
const answer = ref("");

const imgSrc = computed(() => {
  return `/champion/tiles/${answer.value.name}_0.jpg`;
});

onMounted(async () => {
  try {
    const res = await fetch(`${API_URL}/champions`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    champions.value = json.data;
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
      <input type="text" placeholder="Choisissez un champion..." />

      <table>
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
        <tr>
          <td><img :src="imgSrc" alt="" width="60px" /></td>
          <td>{{ answer.gender }}</td>
          <td>{{ answer.role }}</td>
          <td>{{ answer.species }}</td>
          <td>{{ answer.resource }}</td>
          <td>{{ answer.range_type }}</td>
          <td>{{ answer.region }}</td>
          <td>{{ answer.release_year }}</td>
        </tr>
      </table>

      <p>Champion à trouver : {{ answer.name }}</p>
    </div>
  </div>
</template>
