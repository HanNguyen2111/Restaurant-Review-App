<template>
  <div id="map" style="height: 400px;"></div>
</template>

<script>
import L from 'leaflet';
export default {
  name: 'Map',
  props: {
    restaurants: Array,
  },
  data() {
    return {
      map: null,
    };
  },
  watch: {
    restaurants(newRestaurants) {
      if (newRestaurants.length && this.map) {
        newRestaurants.forEach(r => {
          if (r.lat && r.long) {
            L.marker([r.lat, r.long])
                    .setIcon(L.icon({
                    iconUrl: new URL('../assets/marker.png', import.meta.url).href,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32],

                }))
              .addTo(this.map)
              .bindPopup(`<b>${r.name}</b><br>${r.type}`);
          }
        });
      }
    },
  },
  mounted() {
    this.map = L.map('map').setView([-37.8136, 144.9631], 13);
    let circle = L.circle([-37.8136, 144.9631], {
      color: 'orange',
      fillColor: 'yellow',
      fillOpacity: 0.3,
      radius: 1000,
    }).addTo(this.map);

    circle.bindPopup('Melbourne CBD');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(this.map);
  },
};
</script>
