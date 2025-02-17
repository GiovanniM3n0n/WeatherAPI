<template>
  <div class="home-container">
    <button @click="toggleTheme" class="theme-toggle">
      {{ isDarkMode ? '☀️' : '🌙' }}
    </button>
    <h1>🌦️ Weather App</h1>

    <div class="content">
      <WeatherSearch @search="handleSearch" />
      
      <div class="weather-info">
        <transition name="fade" mode="out-in">
          <WeatherCard
            v-if="searchedWeather"
            :weather="searchedWeather"
            clearable
            @clear="clearSearch"
          />
          <WeatherCard v-else-if="userLocationWeather" :weather="userLocationWeather" />
        </transition>

        <WeatherForecast v-if="forecast.length" :forecast="forecast" />

        <p v-if="locationError" class="error-message">{{ locationError }}</p>
        <p v-else-if="isLoading" class="loading-message">Carregando clima da sua localização...</p>
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
import WeatherForecast from "@/components/WeatherForecast.vue";
import { fetchWeather, fetchForecast, fetchHistory } from "@/services/apiService";

export default {
  components: {
    WeatherSearch,
    WeatherHistory,
    WeatherCard,
    WeatherForecast,
  },
  setup() {
    const history = ref([]);
    const isDarkMode = ref(false);
    const userLocationWeather = ref(null);
    const searchedWeather = ref(null);
    const forecast = ref([]);
    const locationError = ref("");
    const isLoading = ref(true);

    const handleSearch = async (newEntry) => {
      if (newEntry) {
        searchedWeather.value = newEntry;
        history.value.unshift(newEntry);
        if (history.value.length > 10) {
          history.value.pop();
        }
        forecast.value = await fetchForecast(newEntry.city);
      }
    };

    const fetchUserLocationWeather = async (latitude, longitude) => {
      try {
        const result = await fetchWeather(`${latitude},${longitude}`);
        userLocationWeather.value = result;
        forecast.value = await fetchForecast(result.city);
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

    const clearSearch = () => {
      searchedWeather.value = null;
      getUserLocation();
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
      forecast,
      locationError,
      isLoading,
      toggleTheme,
      handleSearch,
      clearSearch,
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.error-message {
  color: #ff4d4d;
  margin-top: 20px;
}

.loading-message {
  color: var(--text-color);
  margin-top: 20px;
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

  .theme-toggle {
    top: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    font-size: 1.2em;
  }
}
</style>
