import { ref } from "vue";

export const isConnected = ref(localStorage.getItem("userId") !== null);

export function login(userId) {
  localStorage.setItem("userId", userId);
  isConnected.value = true;
}

export function logout() {
  localStorage.clear();
  isConnected.value = false;
}
