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
  color: var(--color-heading);
  text-align: center;
}

.history-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.history-item {
  background-color: var(--color-background-soft);
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 0.5rem;
  box-shadow: var(--vt-c-shadow-light);
  transition: transform 0.3s, box-shadow 0.3s;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.history-item:hover {
  transform: translateY(-2px);
  box-shadow: var(--vt-c-shadow-dark);
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
  color: var(--color-heading);
}

.history-temp {
  color: var(--color-text);
  font-size: 1.1rem;
}

.history-description {
  color: var(--color-text);
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

@media (prefers-color-scheme: dark) {
  .history-title {
    color: var(--vt-c-text-dark-1);
  }

  .history-item {
    background-color: var(--vt-c-black-soft);
    box-shadow: var(--vt-c-shadow-dark);
  }

  .history-city {
    color: var(--vt-c-text-dark-1);
  }

  .history-temp,
  .history-description {
    color: var(--vt-c-text-dark-2);
  }

  .history-item:hover {
    background-color: var(--vt-c-black-mute);
  }
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