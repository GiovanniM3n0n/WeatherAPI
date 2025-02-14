export const fetchWeather = async (location) => {
  const apiUrl = location.includes(",")
    ? `http://localhost:8080?lat=${location.split(",")[0]}&lon=${location.split(",")[1]}`
    : `http://localhost:8080?city=${location}`;

  const response = await fetch(apiUrl);
  if (!response.ok) {
    throw new Error("Erro ao buscar clima");
  }
  return response.json();
};

  
  export const fetchHistory = async () => {
    const response = await fetch("http://localhost:8080?history=true");
    if (!response.ok) {
      throw new Error("Erro ao buscar histórico");
    }
    return response.json();
  };

