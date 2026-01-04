<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: calc(100vh - 64px); }
    </style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar (Simplified for Map View) -->
    <div class="hidden md:flex flex-col w-16 bg-gray-900 text-white items-center py-4">
        <a href="/" class="mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </a>
    </div>

    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 z-10 shadow">
            <h1 class="text-lg font-bold">TLC Points Map</h1>
            <a href="/" class="text-blue-600">Back to Dashboard</a>
        </header>

        <main class="flex-1 relative">
            <div id="map"></div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Center on NYC
            var map = L.map('map').setView([40.7128, -74.0060], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Fetch Points
            fetch('/api/points')
                .then(response => response.json())
                .then(data => {
                    data.points.forEach(point => {
                        let color = 'blue';
                        if(point.type === 'gas') color = 'red';
                        if(point.type === 'ev') color = 'green';

                        L.marker([point.lat, point.lng]).addTo(map)
                            .bindPopup(`<b>${point.name}</b><br>Type: ${point.type}`);
                    });
                });
        });
    </script>
</body>
</html>
