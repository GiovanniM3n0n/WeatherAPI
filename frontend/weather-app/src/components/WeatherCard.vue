<template>
  <div class="weather-card">
    <div class="weather-card-header">
      <h2 class="weather-city">{{ weather.city }}</h2>
      <WeatherIcon :key="weather.weather_description" :condition="weather.weather_description" />
    </div>
    <div class="weather-main">
      <div class="weather-temp">{{ Math.round(weather.temperature) }}°C</div>
      <div class="weather-description">{{ weather.weather_description }}</div>
    </div>
    <div class="weather-details">
      <div class="weather-detail">
        <span class="detail-icon">💧</span>
        <span class="detail-label">Humidade</span>
        <span class="detail-value">{{ weather.humidity }}%</span>
      </div>
      <div class="weather-detail">
        <span class="detail-icon">💨</span>
        <span class="detail-label">Vento</span>
        <span class="detail-value">{{ weather.wind_speed }} km/h</span>
      </div>
      <div class="weather-detail">
        <span class="detail-icon">🌡️</span>
        <span class="detail-label">Sensação térmica</span>
        <span class="detail-value">{{ Math.round(weather.feels_like) }}°C</span>
      </div>
      <div class="weather-detail">
        <span class="detail-icon">☁️</span>
        <span class="detail-label">Nebulosidade</span>
        <span class="detail-value">{{ weather.cloudiness }}%</span>
      </div>
    </div>
  </div>
</template>

<script>
import WeatherIcon from "@/components/WeatherIcon.vue";

export default {
  components: {
    WeatherIcon,
  },
  props: {
    weather: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style scoped>
.weather-card {
  background-color: #ffffff;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  width: 300px;
  height: 400px; 
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow: hidden;
}

.weather-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.weather-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.weather-city {
  font-size: 1.5rem;
  color: #2c3e50;
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 70%; 
}

.weather-icon {
  font-size: 2.5rem;
  flex-shrink: 0;
}

.weather-main {
  text-align: center;
  margin-bottom: 1.5rem;
}

.weather-temp {
  font-size: 2.5rem;
  font-weight: bold;
  color: #3498db;
  margin: 0.5rem 0;
}

.weather-description {
  font-size: 1.1rem;
  color: #7f8c8d;
  text-transform: capitalize;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.weather-details {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.weather-detail {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 0.75rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: background-color 0.3s;
}

.weather-detail:hover {
  background-color: #e9ecef;
}

.detail-icon {
  font-size: 1.5rem;
  margin-bottom: 0.25rem;
}

.detail-label {
  font-size: 0.9rem;
  color: #7f8c8d;
  text-align: center;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.detail-value {
  font-size: 1.1rem;
  font-weight: bold;
  color: #2c3e50;
}

.dark-mode .weather-card {
  background-color: #2c3e50;
}

.dark-mode .weather-city {
  color: #ecf0f1;
}

.dark-mode .weather-temp {
  color: #3498db;
}

.dark-mode .weather-description {
  color: #bdc3c7;
}

.dark-mode .weather-detail {
  background-color: #34495e;
}

.dark-mode .detail-label {
  color: #bdc3c7;
}

.dark-mode .detail-value {
  color: #ecf0f1;
}

@media (max-width: 768px) {
  .weather-card {
    width: 100%;
    height: auto;
  }

  .weather-card-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .weather-icon {
    margin-top: 0.5rem;
  }

  .weather-details {
    grid-template-columns: 1fr;
  }
}
</style>