<template>
  <div class="search-container">
    <div class="search-box">
      <input
        v-model="city"
        placeholder="Digite o nome da cidade..."
        class="search-input"
        @input="fetchSuggestions"
        @keydown.enter="handleEnter"
        @keydown.down.prevent="moveDown"
        @keydown.up.prevent="moveUp"
        @keydown.esc="clearSuggestions"
      />
      <button @click="searchWeather" class="search-button">
        🔍 Buscar
      </button>
    </div>

    <transition name="fade">
      <ul v-if="suggestions.length > 0" class="suggestions-list">
        <li
          v-for="(suggestion, index) in suggestions"
          :key="index"
          class="suggestion-item"
          :class="{ active: index === activeIndex }"
          @click="selectSuggestion(suggestion)"
        >
          📍 <strong>{{ suggestion.name }}</strong>, {{ suggestion.state }}, {{ suggestion.country }}
        </li>
      </ul>
    </transition>

    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";
import { fetchWeather, fetchCitySuggestions } from "@/services/apiService";

export default {
  emits: ["search"],
  setup(_, { emit }) {
    const city = ref("");
    const weather = ref(null);
    const errorMessage = ref("");
    const suggestions = ref([]);
    const activeIndex = ref(-1);
    const searchContainer = ref(null);

    const clearSuggestions = () => {
      suggestions.value = [];
      activeIndex.value = -1;
    };

    const handleKeydown = (event) => {
      if (event.key === "Escape") {
        clearSuggestions();
      }
    };

    onMounted(() => {
      document.addEventListener("keydown", handleKeydown);
    });

    onUnmounted(() => {
      document.removeEventListener("keydown", handleKeydown);
    });

    const fetchSuggestions = async () => {
      if (city.value.trim().length < 1) {
        clearSuggestions();
        return;
      }

      try {
        const data = await fetchCitySuggestions(city.value);
        suggestions.value = data.map((item) => ({
          name: item.name,
          state: item.state,
          country: item.country,
        }));
        activeIndex.value = -1;
      } catch (error) {
        console.error("Erro ao buscar sugestões:", error);
      }
    };

    const selectSuggestion = (suggestion) => {
      city.value = suggestion.name;
      clearSuggestions();
      searchWeather();
    };

    const moveDown = () => {
      if (activeIndex.value < suggestions.value.length - 1) {
        activeIndex.value++;
      }
    };

    const moveUp = () => {
      if (activeIndex.value > 0) {
        activeIndex.value--;
      }
    };

    const handleEnter = () => {
      if (activeIndex.value !== -1) {
        selectSuggestion(suggestions.value[activeIndex.value]);
      } else {
        searchWeather();
      }
    };

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

    return {
      city,
      weather,
      errorMessage,
      suggestions,
      activeIndex,
      searchContainer,
      fetchSuggestions,
      selectSuggestion,
      moveDown,
      moveUp,
      handleEnter,
      searchWeather,
      clearSuggestions,
    };
  },
};
</script>

<style scoped>
.search-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  background-color: var(--background-color);
  position: relative;
  color: black;
}

.search-box {
  display: flex;
  gap: 10px;
  align-items: center;
}

.search-input {
  flex: 1;
  padding: 12px;
  border: 2px solid #ccc;
  border-radius: 8px;
  font-size: 1em;
  outline: none;
  transition: all 0.3s ease;
}

.search-input:focus {
  border-color: #3498db;
  box-shadow: 0 0 6px rgba(52, 152, 219, 0.5);
}

.search-button {
  padding: 12px 20px;
  background-color: var(--button-background);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1em;
  transition: background-color 0.3s;
}

.search-button:hover {
  filter: brightness(90%);
}

.error-message {
  color: #ff4d4d;
  text-align: center;
  margin-top: 10px;
}

.suggestions-list {
  list-style: none;
  padding: 0;
  margin-top: 5px;
  border-radius: 8px;
  background-color: white;
  position: absolute;
  width: 100%;
  z-index: 1000;
  max-height: 250px;
  overflow-y: auto;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
  border: 1px solid #ccc;
}

.suggestion-item {
  padding: 12px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s;
  display: flex;
  align-items: center;
  font-size: 1rem;
  color: var(--suggestions-text);

}

.suggestion-item.active {
  background-color: #3498db;
  color: white;
  transform: translateX(5px);
  border-radius: 6px;
}

.suggestion-item:hover {
  background-color: #ecf0f1;
  transform: translateX(5px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media (max-width: 480px) {
  .search-container {
    padding: 15px;
  }

  .search-box {
    flex-direction: column;
    gap: 8px;
  }

  .search-input {
    width: 100%;
  }

  .search-button {
    width: 100%;
    padding: 10px;
  }

  .suggestions-list {
    font-size: 0.9rem;
  }
}
</style>
