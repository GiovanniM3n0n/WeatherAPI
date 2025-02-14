<template>
    <div class="weather-card">
      <div class="weather-card-header">
        <h2>{{ weather.city }}</h2>
      </div>
      <div class="weather-details">
        <div class="weather-detail">
          <span class="icon">🌡️</span>
          <span class="label">Temperatura:</span>
          <span class="value">{{ weather.temperature }}°C</span>
        </div>
        <div class="weather-detail">
          <span class="icon">💧</span>
          <span class="label">Umidade:</span>
          <span class="value">{{ weather.humidity }}%</span>
        </div>
        <div class="weather-detail">
          <span class="icon">💨</span>
          <span class="label">Vento:</span>
          <span class="value">{{ weather.wind_speed }} km/h</span>
        </div>
        <div class="weather-detail">
          <span class="icon">📝</span>
          <span class="label">Descrição:</span>
          <span class="value">{{ weather.weather_description }}</span>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
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
    background-color: var(--card-background);
    color: var(--text-color);
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    width: 100%;
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
  </style>