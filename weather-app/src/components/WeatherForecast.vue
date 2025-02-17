<template>
    <div class="weather-forecast">
      <h2 class="forecast-title">Previsão para os Próximos 5 Dias</h2>
      <div class="forecast-days">
        <div v-for="(day, index) in forecast" :key="index" class="forecast-day">
          <div class="day-date">{{ formatDate(day.date) }}</div>
          <WeatherIcon :condition="day.description" class="day-icon" />
          <div class="day-description">{{ day.description }}</div>
          <div class="day-temps">
            <span class="temp-max">{{ day.maxTemp }}°C</span>
            <span class="temp-min">{{ day.minTemp }}°C</span>
          </div>
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
      forecast: {
        type: Array,
        required: true,
        validator: (value) => {
          return value.every((day) => {
            return (
              day.date &&
              day.minTemp !== undefined &&
              day.maxTemp !== undefined &&
              day.description
            );
          });
        },
      },
    },
    methods: {
      formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("pt-BR", {
          weekday: "short",
          day: "numeric",
          month: "short",
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .weather-forecast {
  background: linear-gradient(135deg, var(--background-color), rgba(255, 255, 255, 0.1));
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin-top: 2rem;
}

.forecast-title {
  font-size: 1.75rem;
  color: var(--text-color);
  margin-bottom: 1.5rem;
  text-align: center;
  font-weight: 600;
}

.forecast-days {
  display: flex;
  justify-content: space-between;
  gap: 0.5rem;
  overflow-x: auto;
}

.forecast-day {
  background-color: var(--card-background);
  border-radius: 8px;
  text-align: center;
  flex: 1;
  min-width: 150px;
  max-width: 200px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center; 
  height: 200px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  flex-grow: 1; 
}

.forecast-day:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.day-date {
  font-size: 1rem;
  color: var(--text-color);
  font-weight: 400;
}

.day-icon {
  font-size: 2.5rem;
  margin: 0.5rem 0;
}

.day-description {
  font-size: 1rem;
  color: var(--text-secondary-color);
  text-transform: capitalize;
  margin-bottom: 0.5rem;
  font-weight: 400;
}

.day-temps {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  font-size: 1.1rem;
  margin-top: auto;
}

.temp-max {
  color: #e74c3c;
  font-weight: bold;
}

.temp-min {
  color: #3498db;
  font-weight: bold;
}

.dark-mode .weather-forecast {
  background: linear-gradient(135deg, var(--dark-background-color), rgba(0, 0, 0, 0.1));
}

.dark-mode .forecast-day {
  background-color: var(--dark-card-background);
}

.dark-mode .day-date,
.dark-mode .day-description {
  color: var(--dark-text-color);
}

@media (max-width: 768px) {
  .forecast-days {
    flex-wrap: wrap;
    justify-content: center;
  }

  .forecast-day {
    min-width: 120px;
    max-width: 150px;
    height: 180px;
  }

  .forecast-title {
    font-size: 1.5rem;
  }

  .day-date {
    font-size: 0.9rem;
  }

  .day-icon {
    font-size: 2rem;
  }

  .day-description {
    font-size: 0.9rem;
  }

  .day-temps {
    font-size: 1rem;
  }
}

  </style>