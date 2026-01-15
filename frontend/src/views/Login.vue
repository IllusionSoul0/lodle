<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { login } from "@/stores/auth.js";
import { API_URL } from "../api.js";

const router = useRouter();
const isRegistering = ref(false);
const email = ref("");
const password = ref("");
const pseudo = ref("");
const error = ref("");
const loading = ref(false);

const handleLogin = async () => {
  console.log("jsp");
  error.value = "";
  loading.value = true;

  try {
    const response = await fetch(`${API_URL}/login`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      error.value = data.error || "Échec de la connexion";
      return;
    }
    localStorage.setItem("userId", data.userId);
    login(data.userId);
    router.push("/");
  } catch (err) {
    error.value = "Une erreur s'est produite lors de la connexion";
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const handleRegister = async () => {
  error.value = "";
  loading.value = true;

  try {
    const response = await fetch(`${API_URL}/register`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
        pseudo: pseudo.value,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      error.value = data.error || "Échec de l'inscription";
      return;
    }

    localStorage.setItem("userId", data.userId);
    login(data.userId);
    router.push("/");
  } catch (err) {
    error.value = "Une erreur s'est produite lors de l'inscription";
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const toggleForm = () => {
  isRegistering.value = !isRegistering.value;
  error.value = "";
  email.value = "";
  password.value = "";
  pseudo.value = "";
};

const handleSubmit = async () => {
  if (isRegistering.value) {
    await handleRegister();
  } else {
    await handleLogin();
  }
};
</script>

<template>
  <div class="login-container">
    <form @submit.prevent="handleSubmit">
      <h1>{{ isRegistering ? "Créer un compte" : "Connexion" }}</h1>

      <div v-if="error" class="error-message">
        {{ error }}
      </div>

      <div v-if="isRegistering" class="form-group">
        <label for="pseudo">Nom d'utilisateur:</label>
        <input id="pseudo" v-model="pseudo" type="text" placeholder="Entrez votre nom d'utilisateur" required />
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input id="email" v-model="email" type="email" placeholder="Entrez votre email" required />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe:</label>
        <input id="password" v-model="password" type="password" placeholder="Entrez votre mot de passe" required />
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? (isRegistering ? "Création..." : "Connexion en cours...") : isRegistering ? "Créer un compte" : "Connexion" }}
      </button>
    </form>

    <div class="toggle-form">
      <p>{{ isRegistering ? "Vous avez déjà un compte?" : "Vous n'avez pas de compte?" }}</p>
      <button type="button" @click="toggleForm" class="link-button">
        {{ isRegistering ? "Connexion" : "Inscription" }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

form {
  display: flex;
  flex-direction: column;
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}

input {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

button {
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

button:hover:not(:disabled) {
  background-color: #0056b3;
}

button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

.error-message {
  color: #d32f2f;
  margin-bottom: 15px;
  padding: 10px;
  background-color: #ffebee;
  border-radius: 4px;
}

.toggle-form {
  text-align: center;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #ddd;
}

.toggle-form p {
  margin: 0 0 10px 0;
  color: #666;
}

.link-button {
  background-color: transparent;
  color: #007bff;
  padding: 5px 0;
  border: none;

  .connected-view {
    text-align: center;
  }

  .logout-button {
    padding: 10px 20px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 20px;
  }

  .logout-button:hover {
    background-color: #c82333;
  }
  cursor: pointer;
  text-decoration: underline;
  font-size: 14px;
}

.link-button:hover {
  color: #0056b3;
}
</style>
