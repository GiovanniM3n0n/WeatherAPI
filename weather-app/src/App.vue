<template>
  <div id="app">
    <router-view />
  </div>
</template>

<style>
#app {
  font-family: Arial, sans-serif;
  margin: 20px;
}

:root {
  --background-color: #ffffff;
  --text-color: #333333;
  --card-background: #f9f9f9;
  --button-background: #007bff;
  --button-text: #ffffff;
  --input-border: #cccccc;
  --input-focus-border: #007bff;
}

[data-theme="dark"] {
  --background-color: #121212;
  --text-color: #f0f0f0;
  --card-background: #1e1e1e;
  --button-background: #0056b3;
  --button-text: #f0f0f0;
  --input-border: #555555;
  --input-focus-border: #0056b3;
}

body {
  background-color: var(--background-color);
  color: var(--text-color);
}
</style>
