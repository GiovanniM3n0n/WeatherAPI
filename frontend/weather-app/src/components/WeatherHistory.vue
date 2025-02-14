<template>
    <div class="history-container">
      <h2>Histórico de Pesquisas</h2>
      <ul>
        <li v-for="(item, index) in limitedHistory" :key="index" class="history-item">
          <span class="city">{{ item.city }}</span>
          <span class="details">{{ item.temperature }}°C - {{ item.weather_description }}</span>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      history: {
        type: Array,
        required: true,
      },
    },
    computed: {
      limitedHistory() {
        return this.history.slice(0, 10); 
      },
    },
  };
  </script>
  
  <style scoped>
  .history-container {
    margin-top: 20px;
    background-color: var(--background-color);
    color: var(--text-color);
  }
  
  ul {
    list-style: none;
    padding: 0;
  }
  
  .history-item {
    background-color: var(--card-background);
    color: var(--text-color);
    margin: 10px 0;
    padding: 12px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  
  .history-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }
  
  .city {
    font-weight: bold;
    color: #333;
  }
  
  .details {
    color: #666;
    font-size: 0.9em;
  }
  </style>
  