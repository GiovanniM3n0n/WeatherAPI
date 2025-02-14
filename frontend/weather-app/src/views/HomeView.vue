<template>
  <div class="home-container">
    <button @click="toggleTheme" class="theme-toggle">
      {{ isDarkMode ? '🌞' : '🌙' }}
    </button>
    <h1>🌦️ Weather App</h1>
    <div class="content">
      <WeatherSearch @search="handleSearch" />
      <div class="weather-info">
        <WeatherCard v-if="searchedWeather" :weather="searchedWeather" />
        <WeatherCard v-if="userLocationWeather" :weather="userLocationWeather" />
        <p v-else-if="locationError" class="error-message">{{ locationError }}</p>
        <p v-else class="loading-message">Carregando clima da sua localização...</p>
      </div>
      <WeatherHistory :history="history" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import WeatherSearch from "@/components/WeatherSearch.vue";
import WeatherHistory from "@/components/WeatherHistory.vue";
import WeatherCard from "@/components/WeatherCard.vue";
import { fetchWeather, fetchHistory } from "@/services/apiService";

export default {
  components: {
    WeatherSearch,
    WeatherHistory,
    WeatherCard,
  },
  setup() {
    const history = ref([]);
    const isDarkMode = ref(false);
    const userLocationWeather = ref(null);
    const searchedWeather = ref(null);
    const locationError = ref("");
    const isLoading = ref(true);

    const fetchUserLocationWeather = async (latitude, longitude) => {
      try {
        const result = await fetchWeather(`${latitude},${longitude}`);
        userLocationWeather.value = result;
        locationError.value = "";
      } catch (error) {
        console.error("Erro ao buscar clima da localização", error);
        locationError.value = "Não foi possível carregar o clima da sua localização.";
      } finally {
        isLoading.value = false;
      }
    };

    const getUserLocation = () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const { latitude, longitude } = position.coords;
            fetchUserLocationWeather(latitude, longitude);
          },
          (error) => {
            console.error("Erro ao obter localização", error);
            locationError.value = "Não foi possível obter a sua localização.";
            isLoading.value = false;
          }
        );
      } else {
        locationError.value = "Geolocalização não é suportada pelo seu navegador.";
        isLoading.value = false;
      }
    };

    const toggleTheme = () => {
      isDarkMode.value = !isDarkMode.value;
      document.documentElement.setAttribute(
        "data-theme",
        isDarkMode.value ? "dark" : "light"
      );
    };

    const fetchHistoryData = async () => {
      try {
        history.value = await fetchHistory();
      } catch (error) {
        console.error("Erro ao buscar histórico", error);
      }
    };

    const handleSearch = (newEntry) => {
      if (newEntry) {
        searchedWeather.value = newEntry;
        history.value.unshift(newEntry);
        if (history.value.length > 10) {
          history.value.pop();
        }
      }
    };

    onMounted(() => {
      getUserLocation();
      fetchHistoryData();
    });

    return {
      history,
      isDarkMode,
      userLocationWeather,
      searchedWeather,
      locationError,
      isLoading,
      toggleTheme,
      handleSearch,
    };
  },
};
</script>

<style scoped>
.home-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
  background-color: var(--background-color);
  color: var(--text-color);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: var(--button-background);
  margin-bottom: 20px;
  font-size: 2.5em;
}

.theme-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 10px;
  background-color: var(--button-background);
  color: var(--button-text);
  border: none;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.5em;
  transition: background-color 0.3s;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.theme-toggle:hover {
  background-color: var(--input-focus-border);
}

.content {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  width: 100%;
  max-width: 800px;
}

.weather-info {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.weather-card {
  background-color: var(--card-background);
  color: var(--text-color);
  padding: 20px;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
  margin-top: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.weather-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.weather-card-header {
  text-align: center;
  margin-bottom: 20px;
}

.weather-card-header h2 {
  font-size: 1.8em;
  margin: 0;
  color: var(--button-background);
}

.weather-description {
  font-size: 1.2em;
  margin: 5px 0 0;
  color: var(--text-color);
  text-transform: capitalize;
}

.weather-details {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
  margin-top: 15px;
}

.weather-detail {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 10px;
  background-color: var(--card-detail-background);
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.weather-detail .icon {
  font-size: 1.5em;
  margin-bottom: 5px;
}

.weather-detail .label {
  font-size: 0.9em;
  color: var(--text-secondary-color);
  margin-bottom: 5px;
}

.weather-detail .value {
  font-size: 1.2em;
  font-weight: bold;
  color: var(--text-color);
}

.error-message {
  color: #ff4d4d;
  margin-top: 20px;
}

.loading-message {
  color: var(--text-color);
  margin-top: 20px;
}

:root {
  --card-background: #ffffff;
  --card-detail-background: #f0f0f0;
  --text-secondary-color: #666666;
}

[data-theme="dark"] {
  --card-background: #2c2c2c;
  --card-detail-background: #3c3c3c;
  --text-secondary-color: #aaaaaa;
}

@media (max-width: 768px) {
  .content {
    grid-template-columns: 1fr;
  }

  .weather-card {
    max-width: 100%;
  }

  .theme-toggle {
    top: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    font-size: 1.2em;
  }
}
</style>