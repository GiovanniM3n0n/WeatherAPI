<template>
  <div class="history-container">
    <h2 class="history-title">Histórico de Pesquisas</h2>
    <transition-group name="list" tag="ul" class="history-list">
      <li v-for="(item, index) in limitedHistory" :key="index" class="history-item">
        <div class="history-item-content">
          <WeatherIcon :condition="item.weather_description" class="history-icon" />
          <div class="history-details">
            <span class="history-city">{{ item.city }}</span>
            <span class="history-temp">{{ item.temperature }}°C</span>
          </div>
        </div>
        <span class="history-description">{{ item.weather_description }}</span>
      </li>
    </transition-group>
  </div>
</template>

<script>
import { computed } from "vue";
import WeatherIcon from "@/components/WeatherIcon.vue";

export default {
  components: {
    WeatherIcon,
  },
  props: {
    history: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const limitedHistory = computed(() => props.history.slice(0, 5));

    return { limitedHistory };
  },
};
</script>

<style scoped>
.history-container {
  margin-top: 2rem;
  width: 100%;
}

.history-title {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #2c3e50;
  text-align: center;
}

.history-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.history-item {
  background-color: #ffffff;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 0.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.history-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.history-item-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.history-icon {
  font-size: 1.8rem;
}

.history-details {
  display: flex;
  flex-direction: column;
}

.history-city {
  font-weight: bold;
  color: #2c3e50;
}

.history-temp {
  color: #7f8c8d;
  font-size: 1.1rem;
}

.history-description {
  color: #7f8c8d;
  font-style: italic;
  text-align: right;
  flex-grow: 1;
}

.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.dark-mode .history-title {
  color: #ecf0f1;
}

.dark-mode .history-item {
  background-color: #34495e;
}

.dark-mode .history-city {
  color: #ecf0f1;
}

.dark-mode .history-temp,
.dark-mode .history-description {
  color: #bdc3c7;
}

@media (max-width: 768px) {
  .history-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .history-description {
    margin-top: 0.5rem;
    text-align: left;
  }
}
</style>
