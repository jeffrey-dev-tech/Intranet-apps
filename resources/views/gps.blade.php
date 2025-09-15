<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Office Time In Map</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
  #map { height: 500px; width: 100%; }
  #address { margin-top: 10px; font-size: 1.2em; }
  #timeInBtn { margin-top: 10px; padding: 10px 20px; font-size: 1em; }
</style>
</head>
<body>

<div id="map"></div>
<div id="address">Loading...</div>
<button id="timeInBtn">Time In</button>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
let map;
let userMarker;
const radius = 200; // meters

// ===== Office Coordinates (Sanden) =====
const officeLat = 14.171136;
const officeLon = 121.135838;

let userLat = null;
let userLon = null;
let distanceToOffice = null;

// Haversine formula to calculate distance
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // radius of Earth in meters
    const toRad = x => x * Math.PI / 180;
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const a = Math.sin(dLat/2) ** 2 +
              Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
              Math.sin(dLon/2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Initialize map
function initMap() {
    map = L.map('map').setView([officeLat, officeLon], 16); // center on office

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add office marker and circle
    L.marker([officeLat, officeLon]).bindPopup("Our Office (Sanden)").addTo(map);
    L.circle([officeLat, officeLon], {
        color: 'green',
        fillColor: '#0f0',
        fillOpacity: 0.2,
        radius: radius
    }).addTo(map);

    // Get user location after map is initialized
    getLocation();
}

// Get user location and show marker
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;
                showUserMarker(userLat, userLon);
            },
            (error) => {
                document.getElementById("address").textContent = "Unable to get location.";
                console.error(error);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        document.getElementById("address").textContent = "Geolocation not supported.";
    }
}

// Show user marker and distance
function showUserMarker(lat, lon) {
    distanceToOffice = haversineDistance(lat, lon, officeLat, officeLon);

    const message = `
        ${distanceToOffice <= radius ? '✅ You are inside the office radius!' : `❌ You are ${Math.round(distanceToOffice)}m away from the office. Cannot Time In.`}
        <br>Lat: ${lat.toFixed(6)}, Lon: ${lon.toFixed(6)}
    `;

    document.getElementById("address").innerHTML = message;

    if (!userMarker) {
        userMarker = L.marker([lat, lon]).addTo(map).bindPopup(message).openPopup();
    } else {
        userMarker.setLatLng([lat, lon]).bindPopup(message).openPopup();
    }

    map.setView([lat, lon], 16);
}

// Handle Time In button
document.getElementById("timeInBtn").addEventListener("click", () => {
    if (distanceToOffice === null) {
        alert("User location not detected yet.");
        return;
    }

    if (distanceToOffice <= radius) {
        alert("✅ Time In successful!");
        // Here you can add your logic to record Time In (e.g., send to server)
    } else {
        alert("❌ You are not within the office radius. Cannot Time In.");
    }
});

// Initialize map on page load
window.onload = initMap;
</script>

</body>
</html>
