import { ref, computed } from "vue";
import { API_URL } from "@/api";

export function useGameMode(gamemode) {
  const loading = ref(false);
  const error = ref(null);
  const champions = ref([]);
  const champions_list = ref([]);
  const attempts = ref([]);
  const answer = ref({});
  const found = ref(false);
  const splash = ref("");
  const guess = ref("");
  const userId = ref(localStorage.getItem("userId"));

  const splashImages = import.meta.glob("/public/champion/splash/*", { eager: true, as: "url" });

  const splashIndex = Object.entries(splashImages).map(([path, url]) => ({
    name: path
      .split("/")
      .pop()
      .replace(/\.\w+$/, ""),
    url,
  }));

  const suggestions = computed(() => {
    if (!guess.value) return [];
    return champions_list.value.filter((name) => name.toLowerCase().startsWith(guess.value.toLowerCase()));
  });

  const saveAttemptToDb = async (successful) => {
    if (!userId.value || !answer.value.id) return;

    await fetch(`${API_URL}/stats`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        userId: parseInt(userId.value),
        championId: champions.value.find((c) => c.name === answer.value.name).id,
        gamemode,
        attempts: attempts.value.map((c) => c.name),
        successful,
      }),
    });
  };

  const attempt = () => {
    if (!suggestions.value.length) return;

    const selected = suggestions.value.find((n) => n.toLowerCase() === guess.value.toLowerCase()) ?? suggestions.value[0];

    const champ = champions.value.find((c) => c.name.toLowerCase() === selected.toLowerCase());

    attempts.value.push(champ);

    localStorage.setItem(`${gamemode}Attempts`, JSON.stringify(attempts.value.map((c) => c.name)));

    if (selected.toLowerCase() === answer.value.name.toLowerCase()) {
      found.value = true;
      localStorage.setItem(`${gamemode}Found`, true);
      saveAttemptToDb(true);
    }

    champions_list.value = champions_list.value.filter((n) => n.toLowerCase() !== selected.toLowerCase());
    guess.value = "";
  };

  const quoteSelection = async () => {
    if (gamemode !== "quote") return;

    const res = await fetch(`${API_URL}/quotes/${answer.value.name}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    const quotes_list = json.data;
    answer.value = quotes_list[Math.floor(Math.random() * quotes_list.length)];
  };

  const splashSelection = () => {
    if (gamemode !== "splash") return;

    const champName = answer.value.name.toLowerCase();

    const matchingSplashes = splashIndex.filter((s) =>
      s.name.startsWith(
        champName
          .toLowerCase()
          .replace(/(?:^|[\s'])(\w)/g, (_, c) => c.toUpperCase())
          .replace(/[\s'.]/g, "")
      )
    );

    if (!matchingSplashes.length) {
      console.warn("No splash found for", champName);
      splash.value = "";
      return;
    }

    answer.value = { name: answer.value.name, splash: matchingSplashes[Math.floor(Math.random() * matchingSplashes.length)].url };
  };

  const resetGame = async () => {
    if (!found.value && attempts.value.length) {
      await saveAttemptToDb(false);
    }

    attempts.value = [];
    champions_list.value = champions.value.map((c) => c.name);
    answer.value = champions.value[Math.floor(Math.random() * champions.value.length)];

    if (gamemode === "quote") {
      await quoteSelection();
    } else if (gamemode === "splash") {
      splashSelection();
    }

    localStorage.setItem(`${gamemode}Answer`, JSON.stringify(answer.value));
    localStorage.removeItem(`${gamemode}Attempts`);
    localStorage.setItem(`${gamemode}Found`, false);
    found.value = false;
  };

  const init = async () => {
    try {
      const res = await fetch(`${API_URL}/champions`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const json = await res.json();
      champions.value = json.data;
      champions_list.value = champions.value.map((c) => c.name);

      found.value = localStorage.getItem(`${gamemode}Found`) === "true";
      const savedAnswer = localStorage.getItem(`${gamemode}Answer`);
      const savedAttempts = localStorage.getItem(`${gamemode}Attempts`);

      if (savedAnswer && savedAttempts && savedAnswer !== "undefined") {
        answer.value = JSON.parse(savedAnswer);
        const attemptNames = JSON.parse(savedAttempts);
        attempts.value = attemptNames.map((name) => champions.value.find((c) => c.name === name));
        champions_list.value = champions_list.value.filter((name) => !attemptNames.includes(name));
      } else {
        const answerIndex = Math.floor(Math.random() * champions.value.length);
        answer.value = champions.value[answerIndex];

        if (gamemode === "quote") {
          await quoteSelection();
        } else if (gamemode === "splash") {
          splashSelection();
        }

        localStorage.setItem(`${gamemode}Answer`, JSON.stringify(answer.value));
      }
    } catch (e) {
      error.value = e;
      console.error(e);
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    error,
    champions,
    champions_list,
    attempts,
    answer,
    splash,
    found,
    guess,
    suggestions,
    attempt,
    resetGame,
    init,
  };
}
