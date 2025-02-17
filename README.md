# 🌦️ Weather App 🌍

Bem-vindo ao **Weather App**, um aplicativo Full Stack para consultar a previsão do tempo em tempo real!

## 📌 Tecnologias Utilizadas

### **Frontend**:
- Vue.js 3 + Vite

### **Backend**:
- PHP 8.0 (com Nginx)
- MySQL 8.0
- OpenWeather API

### **Ferramentas e Configuração**:
- Docker + Docker Compose 
- PHP PDO para conexão com o banco de dados 

---

## 📌 Como Rodar o Projeto com Docker?

### **💻 1️⃣ Rodando com Docker (Recomendado)**
> **Pré-requisitos**: Ter **Docker** e **Docker Compose** instalados.

1️⃣ Clone este repositório:
```bash
git clone https://github.com/GiovanniM3n0n/WeatherAPI.git
cd weather-app
```

2️⃣ Construa e inicie os containers:
```bash
docker-compose up --build
```

3️⃣ O backend e o frontend estarão disponíveis em:
- **Frontend**: [`http://localhost:5173`](http://localhost:5173)
- **Backend**: [`http://localhost:8080`](http://localhost:8080)

4️⃣ Acesse a interface web e pesquise pelo clima de uma cidade! 🌎

---

## 🌍 Frontend (Vue.js)

O frontend é uma aplicação Vue.js 3 utilizando Vite. Siga os passos abaixo para rodá-lo corretamente.

1️⃣ Vá para a pasta do frontend:
```bash
cd weather-app
```

2️⃣ Instale as dependências do projeto:
```bash
npm install
```

3️⃣ Rode o servidor de desenvolvimento:
```bash
npm run dev
```

4️⃣ Acesse o frontend no navegador em [`http://localhost:5173`](http://localhost:5173).

---

## ⚙️ **Configuração da API OpenWeather**

1️⃣ **Crie uma conta** no OpenWeather e **pegue sua API Key**:
   - Site: [https://openweathermap.org/api](https://openweathermap.org/api)

2️⃣ **Adicione a API Key** no código:
   - No **backend (`src/routes/api.php`)**, altere:
     ```php
     $apiKey = "SUA_API_KEY_AQUI";
     ```

---