<script setup>
import { ref, computed, onMounted } from "vue";
import { API_URL } from "@/api";

const userId = ref(localStorage.getItem("userId"));
const stats = ref([]);
const loading = ref(true);
const error = ref(null);
const champions = ref([]);

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
        <div v-for="(gamemode, index) in Object.keys(statsByGamemode)" :key="gamemode" class="gamemode-section">
          <h2>{{ gamemode.charAt(0).toUpperCase() + gamemode.slice(1) }}</h2>

          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-label">Parties jouées</div>
              <div class="stat-value">{{ getGamemodeStats(gamemode).total }}</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Réussites</div>
              <div class="stat-value green">{{ getGamemodeStats(gamemode).successful }}</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Échecs</div>
              <div class="stat-value red">{{ getGamemodeStats(gamemode).failed }}</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Taux de réussite</div>
              <div class="stat-value">{{ getGamemodeStats(gamemode).successRate }}%</div>
            </div>

            <div class="stat-card">
              <div class="stat-label">Nombre d'essais moyen</div>
              <div class="stat-value">{{ getGamemodeStats(gamemode).averageTries }}</div>
            </div>
          </div>

          <div class="champions-section">
            <h3>Champions les plus devinés</h3>
            <div class="champions-list">
              <div v-for="(championStat, championId) in getGamemodeStats(gamemode).champions" :key="championId" class="champion-item">
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
}

h1 {
  text-align: center;
  margin-bottom: 30px;
  color: #333;
}

h2 {
  margin-top: 30px;
  margin-bottom: 15px;
  color: #555;
  border-bottom: 2px solid #007bff;
  padding-bottom: 10px;
}

h3 {
  color: #666;
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
  color: #d32f2f;
  background-color: #ffebee;
  border-radius: 4px;
}

.no-stats {
  color: #666;
  background-color: #f5f5f5;
  border-radius: 4px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
  margin-bottom: 30px;
}

.stat-card {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-label {
  font-size: 12px;
  color: #999;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
}

.stat-value {
  font-size: 28px;
  font-weight: bold;
  color: #007bff;
}

.stat-value.green {
  color: #4caf50;
}

.stat-value.red {
  color: #d32f2f;
}

.champions-section {
  background-color: #f5f5f5;
  border-radius: 8px;
  padding: 20px;
  margin-top: 20px;
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
  background-color: white;
  padding: 12px;
  border-radius: 4px;
  border-left: 4px solid #007bff;
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
}

.champion-name {
  font-weight: bold;
  color: #333;
}

.champion-usage {
  font-size: 12px;
  color: #999;
}

.champion-success {
  background-color: #e3f2fd;
  color: #1976d2;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
}

.gamemode-section {
  margin-bottom: 40px;
}
</style>
