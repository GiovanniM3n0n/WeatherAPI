<template>
  <span class="weather-icon" :title="condition">{{ weatherIcon }}</span>
</template>

<script>
import { computed } from "vue";

export default {
  props: {
    condition: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const weatherIcons = {
      "céu limpo": "☀️",
      "algumas nuvens": "⛅",
      "nublado": "☁️",
      "nuvens dispersas": "🌥️",
      "nuvens quebradas": "🌤️",
      "neblina": "🌫️",
      "névoa": "🌫️",
      "nevoeiro": "🌫️",
      "chuvisco": "🌦️",
      "garoa": "🌦️",
      "chuva fraca": "🌧️",
      "chuva moderada": "🌧️",
      "chuva forte": "🌧️🌧️",
      "tempestade": "⛈️",
      "trovoada": "⛈️",
      "trovoadas com chuva leve": "⛈️🌧️",
      "trovoadas com chuva forte": "⛈️🌧️🌧️",
      "granizo": "🌨️🧊",
      "neve leve": "🌨️",
      "neve moderada": "❄️🌨️",
      "neve forte": "❄️❄️",
      "chuva congelante": "🌧️❄️",
      "vento forte": "💨",
      "tornado": "🌪️",
      "areia": "🏜️",
      "poeira": "🌪️",
      "fumaça": "🌫️🔥",
    };

    const weatherIcon = computed(() => {
      const lowercaseCondition = props.condition.toLowerCase();

      for (const [key, icon] of Object.entries(weatherIcons)) {
        if (lowercaseCondition.includes(key)) {
          return icon;
        }
      }

      return "❓";
    });

    return { weatherIcon };
  },
};
</script>

<style scoped>
.weather-icon {
  font-size: 1.5em;
  line-height: 1;
  margin-left: 5px;
}
</style>