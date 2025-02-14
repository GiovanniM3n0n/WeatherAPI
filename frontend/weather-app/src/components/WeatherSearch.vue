<template>
  <div class="search-container">
    <div class="search-box">
      <input
        v-model="city"
        placeholder="Digite o nome da cidade..."
        class="search-input"
      />
      <button @click="searchWeather" class="search-button">
        🔍 Buscar
      </button>
    </div>

    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
  </div>
</template>

<script>
import { ref } from "vue";
import { fetchWeather } from "@/services/apiService";
import WeatherCard from "@/components/WeatherCard.vue"; // Importe o componente

export default {
  emits: ["search"],
  setup(_, { emit }) {
    const city = ref("");
    const weather = ref(null);
    const errorMessage = ref("");

    const searchWeather = async () => {
      if (!city.value.trim()) {
        errorMessage.value = "Digite uma cidade!";
        return;
      }

      try {
        const result = await fetchWeather(city.value);
        weather.value = result;
        errorMessage.value = "";
        emit("search", result);
      } catch (error) {
        console.error(error);
        errorMessage.value = "Erro ao buscar clima!";
      }
    };

    return { city, weather, errorMessage, searchWeather };
  },
};
</script>

<style scoped>
.search-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  background-color: var(--background-color);
  color: var(--text-color);
}

.search-box {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.search-input {
  border-color: var(--input-border);
  flex: 1;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1em;
  outline: none;
  transition: border-color 0.3s;
}

.search-input:focus {
  border-color: var(--input-focus-border);
}

.search-button {
  padding: 10px 20px;
  background-color: var(--button-background);
  color: var(--button-text);
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1em;
  transition: background-color 0.3s;
}

.search-button:hover {
  background-color: var(--input-focus-border);
}

.error-message {
  color: #ff4d4d;
  text-align: center;
  margin: 10px 0;
}
</style>