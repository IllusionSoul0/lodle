<script setup>
import { ref, onMounted, computed } from "vue";
import { API_URL } from "@/api";
import { imgSrc } from "@/utils/imgSrc.js";
import { useGameMode } from "@/components/useGameMode";

const { loading, error, champions, champions_list, attempts, answer, quote, found, guess, suggestions, attempt, resetGame } = useGameMode("classic");

onMounted(async () => {
  try {
    const res = await fetch(`${API_URL}/champions`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    champions.value = json.data;
    champions_list.value = data.map((champion) => champion.name);

    // Select a random champion as the answer
    answer.value = champions.value[Math.floor(Math.random() * champions.value.length)];
    const reponse = await fetch(`${API_URL}/quotes/${answer.value.name}`);
    if (!reponse.ok) {
      throw new Error("Failed to fetch quote");
    }
    quote.value = await reponse.json().quote;
    console.log(quote.value);
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <h1>AAAAA</h1>
</template>
