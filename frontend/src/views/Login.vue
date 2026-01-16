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
      <div class="icon-circle">
        <img src="../assets/icons/logo-login.png" alt="User Icon" class="user-icon" />
      </div>
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
      <button type="button" @click="toggleForm" class="link-inscription-btn">
        {{ isRegistering ? "Connexion" : "Inscription" }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.login-container {
  background-color: rgba(0, 0, 0, 0.75);
  max-width: 400px;
  margin: 50px auto;
  padding: 40px;
  border-radius: 15px;
  color: #f0e6d2;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
}

form {
  display: flex;
  flex-direction: column;
}

.icon-circle {
  width: auto;
  height: auto;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto 15px;
}

.user-icon {
  width: 4rem;
  height: auto;
}

h1 {
  text-align: center;
  margin-bottom: 20px;
  color: #c89b3c;
  font-size: 2rem;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.form-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
  color: #a09b8c;
  text-transform: uppercase;
  font-size: 12px;
  letter-spacing: 0.5px;
}

input {
  padding: 12px;
  background-color: #1e2328;
  border: 2px solid #5b5a56;
  border-radius: 5px;
  font-size: 14px;
  color: #f0e6d2;
  transition: border-color 0.3s ease;
}

input:focus {
  outline: none;
  border-color: #c89b3c;
}

button[type="submit"] {
  padding: 12px;
  background-color: #c89b3c;
  color: #1e2328;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  margin-top: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 0 10px rgba(200, 155, 60, 0.2);
}

button[type="submit"]:hover:not(:disabled) {
  background-color: #a07b30;
  box-shadow: 0 0 20px rgba(200, 155, 60, 0.5);
}

button[type="submit"]:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
  box-shadow: none;
}

.error-message {
  color: #ffcccc;
  margin-bottom: 15px;
  padding: 10px;
  background-color: rgba(220, 53, 69, 0.2);
  border-radius: 4px;
  border: 1px solid #dc3545;
}

.toggle-form {
  text-align: center;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 2px solid #5b5a56;
}

.toggle-form p {
  margin: 0 0 10px 0;
  color: #a09b8c;
}

.link-inscription-btn {
  background-color: transparent;
  color: #c89b3c;
  padding: 5px 10px;
  border: 2px solid #c89b3c;
  border-radius: 4px;
  cursor: pointer;
  text-decoration: none;
  font-size: 14px;
  font-weight: bold;
  transition: all 0.3s ease;
}

.link-inscription-btn:hover {
  background-color: #c89b3c;
  color: #1e2328;
}

.connected-view {
  text-align: center;
}

.logout-button {
  padding: 12px 20px;
  background-color: transparent;
  color: #c89b3c;
  border: 2px solid #c89b3c;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  margin-top: 20px;
  transition: all 0.3s ease;
  box-shadow: 0 0 10px rgba(200, 155, 60, 0.2);
}

.logout-button:hover {
  background-color: #c89b3c;
  color: #1e2328;
  box-shadow: 0 0 20px rgba(200, 155, 60, 0.5);
}
</style>
