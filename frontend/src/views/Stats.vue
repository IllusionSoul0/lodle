<script setup>
import { ref, computed, onMounted } from "vue";
import { API_URL } from "@/api";

const userId = ref(localStorage.getItem("userId"));
const stats = ref([]);
const loading = ref(true);
const error = ref(null);
const champions = ref([]);
const selectedGamemode = ref(null);

// Get the image path locally based on champion name
const imgSrc = (champion) => {
  const champion_name = champion
    .toLowerCase()
    .replace(/(?:^|[\s'])(\w)/g, (_, c) => c.toUpperCase())
    .replace(/[\s'.]/g, "");
  return `/champion/tiles/${champion_name}.png`;
};

const statsByGamemode = computed(() => {
  const grouped = {};

  stats.value.forEach((stat) => {
    if (!grouped[stat.gamemode]) {
      grouped[stat.gamemode] = [];
    }
    grouped[stat.gamemode].push(stat);
  });

  return grouped;
});

const availableGamemodes = computed(() => {
  return Object.keys(statsByGamemode.value).sort();
});

const currentGamemode = computed(() => {
  if (!selectedGamemode.value && availableGamemodes.value.length > 0) {
    return availableGamemodes.value[0];
  }
  return selectedGamemode.value;
});

const getGamemodeStats = (gamemode) => {
  const gamemodeStats = statsByGamemode.value[gamemode] || [];

  if (gamemodeStats.length === 0) {
    return {
      total: 0,
      successful: 0,
      failed: 0,
      successRate: 0,
      averageTries: 0,
      champions: {},
    };
  }

  const successful = gamemodeStats.filter((s) => s.successful).length;
  const failed = gamemodeStats.length - successful;
  const successRate = Math.round((successful / gamemodeStats.length) * 100);
  const averageTries = gamemodeStats.reduce((sum, s) => sum + (s.nb_try || 0), 0) / gamemodeStats.length;

  const champions = {};
  gamemodeStats.forEach((stat) => {
    const championId = stat.id_champion;
    if (!champions[championId]) {
      champions[championId] = { count: 0, successful: 0 };
    }
    champions[championId].count += 1;
    if (stat.successful) {
      champions[championId].successful += 1;
    }
  });

  return {
    total: gamemodeStats.length,
    successful,
    failed,
    successRate,
    averageTries: averageTries.toFixed(1),
    champions,
  };
};

const getChampionName = (championId) => {
  const champion = champions.value.find((c) => c.id == championId);
  return champion ? champion.name : `Champion ${championId}`;
};

onMounted(async () => {
  if (!userId.value) {
    error.value = "Vous devez être connecté pour voir vos statistiques.";
    loading.value = false;
    return;
  }

  try {
    const championsResponse = await fetch(`${API_URL}/champions`);
    if (championsResponse.ok) {
      const championsData = await championsResponse.json();
      champions.value = championsData.data || [];
    }

    const response = await fetch(`${API_URL}/stats/user/${userId.value}`);

    if (!response.ok) {
      throw new Error("Erreur lors de la récupération des statistiques");
    }

    const data = await response.json();
    stats.value = data.data || [];
  } catch (err) {
    error.value = err.message;
    console.error(err);
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="stats-container">
    <h1>Statistiques de l'utilisateur</h1>

    <div v-if="loading" class="loading">Chargement...</div>

    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else class="stats-content">
      <div v-if="Object.keys(statsByGamemode).length === 0" class="no-stats">
        <p>Aucune statistique disponible. Jouez d'abord!</p>
      </div>

      <div v-else>
        <div class="gamemode-selector">
          <button
            v-for="gamemode in availableGamemodes"
            :key="gamemode"
            :class="['gamemode-button', { active: currentGamemode === gamemode }]"
            @click="selectedGamemode = gamemode"
          >
            {{ gamemode.charAt(0).toUpperCase() + gamemode.slice(1) }}
          </button>
        </div>

        <div v-if="currentGamemode" class="gamemode-section">
          <h2>{{ currentGamemode.charAt(0).toUpperCase() + currentGamemode.slice(1) }}</h2>

          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-label">Parties jouées</div>
              <div class="stat-value">{{ getGamemodeStats(currentGamemode).total }}</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Réussites</div>
              <div class="stat-value green-text">{{ getGamemodeStats(currentGamemode).successful }}</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Échecs</div>
              <div class="stat-value red-text">{{ getGamemodeStats(currentGamemode).failed }}</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Taux de réussite</div>
              <div class="stat-value">{{ getGamemodeStats(currentGamemode).successRate }}%</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Nombre d'essais moyen</div>
              <div class="stat-value">{{ getGamemodeStats(currentGamemode).averageTries }}</div>
            </div>
          </div>

          <div class="champions-section">
            <h3>Champions les plus devinés</h3>
            <div class="champions-list">
              <div v-for="(championStat, championId) in getGamemodeStats(currentGamemode).champions" :key="championId" class="champion-item">
                <div class="left-part">
                  <div class="champion-header">
                    <img :src="imgSrc(getChampionName(championId))" alt="" class="champion-image" />
                    <span class="champion-name">{{ getChampionName(championId) }}</span>
                  </div>
                  <span class="champion-usage">{{ championStat.count }} partie(s)</span>
                </div>
                <div class="champion-success">{{ championStat.successful }}/{{ championStat.count }} réussi(s)</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.stats-container {
  max-width: 1000px;
  margin: 20px auto;
  padding: 20px;
  background-color: rgba(0, 0, 0, 0.75);
  border-radius: 15px;
  color: #f0e6d2;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
}

h1 {
  text-align: center;
  margin-bottom: 30px;
  color: #c89b3c;
  font-size: 2.5rem;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.gamemode-selector {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
  flex-wrap: wrap;
  justify-content: center;
}

.gamemode-button {
  padding: 10px 20px;
  border: 2px solid #c89b3c;
  background-color: #1e2328;
  color: #c89b3c;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.gamemode-button:hover {
  background-color: #32383e;
}

.gamemode-button.active {
  background-color: #c89b3c;
  color: #1e2328;
}

h2 {
  margin-top: 30px;
  margin-bottom: 15px;
  color: #c89b3c;
  border-bottom: 2px solid #5b5a56;
  padding-bottom: 10px;
}

h3 {
  color: #c89b3c;
  margin-top: 20px;
  margin-bottom: 15px;
}

.loading,
.error,
.no-stats {
  text-align: center;
  padding: 40px;
  font-size: 18px;
}

.error {
  color: #ffcccc;
  background-color: rgba(220, 53, 69, 0.2);
  border-radius: 4px;
  border: 1px solid #dc3545;
}

.no-stats {
  color: #a09b8c;
  background-color: rgba(91, 90, 86, 0.2);
  border-radius: 4px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
  margin-bottom: 30px;
}

.stat-card {
  background-color: #1e2328;
  border: 2px solid #5b5a56;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.stat-label {
  font-size: 12px;
  color: #a09b8c;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
}

.stat-value {
  font-size: 28px;
  font-weight: bold;
  color: #c89b3c;
}

.stat-value.green-text {
  color: #28a745;
}

.stat-value.red-text {
  color: #dc3545;
}

.champions-section {
  background-color: rgba(91, 90, 86, 0.15);
  border-radius: 8px;
  padding: 20px;
  margin-top: 20px;
  border: 1px solid #5b5a56;
}

.champions-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.champion-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #1e2328;
  padding: 12px;
  border-radius: 4px;
  border-left: 4px solid #c89b3c;
  border: 2px solid #5b5a56;
  border-left: 4px solid #c89b3c;
}

.left-part {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.champion-header {
  display: flex;
  align-items: center;
  gap: 10px;
}

.champion-image {
  width: 40px;
  height: 40px;
  border-radius: 4px;
  border: 1px solid #c89b3c;
}

.champion-name {
  font-weight: bold;
  color: #f0e6d2;
}

.champion-usage {
  font-size: 12px;
  color: #a09b8c;
}

.champion-success {
  background-color: rgba(200, 155, 60, 0.2);
  color: #c89b3c;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
  border: 1px solid #c89b3c;
}

.gamemode-section {
  margin-bottom: 40px;
}
</style>
