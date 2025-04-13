document.addEventListener("DOMContentLoaded", () => {
    const map = L.map('map').setView([48.05, 6.9], 9); // Centrage Vosges

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    const stations = [
        { name: "La Bresse", lat: 48.0022, lon: 6.8807 },
        { name: "Gérardmer", lat: 48.0678, lon: 6.8755 },
        { name: "Ventron", lat: 47.9475, lon: 6.8731 },
        { name: "Le Champ du Feu", lat: 48.3963, lon: 7.2308 },
        { name: "Ballon d’Alsace", lat: 47.8350, lon: 6.8440 }
    ];

    stations.forEach(station => {
        L.marker([station.lat, station.lon])
            .addTo(map)
            .bindPopup(`<strong>${station.name}</strong><br><a href="neige.php?station=${encodeURIComponent(station.name)}">Voir les prévisions</a>`);
    });
});
