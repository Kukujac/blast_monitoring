let map = L.map('map').setView([5.6, -0.2], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19
}).addTo(map);

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
}

function showTab(tab) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
  document.getElementById(tab).classList.add('active');
}

// Load hole_data
fetch('php/get_hole_data.php')
  .then(res => res.json())
  .then(data => {
    data.forEach(fc => {
      fc.features.forEach(feature => {
        const coords = feature.geometry.coordinates;
        const props = feature.properties;
        const marker = L.circleMarker([coords[1], coords[0]], {
          color: props.type === 'actual' ? 'red' : props.type === 'design' ? 'blue' : 'green'
        }).addTo(map);
        marker.bindPopup(`
          <strong>Hole ID:</strong> ${props.hole_id}<br/>
          <strong>Elevation:</strong> ${props[props.type + '_elevation']}
        `);
      });
    });
  });

// Load blast monitoring
fetch('php/get_blasting_monitoring.php')
  .then(res => res.json())
  .then(data => {
    data.features.forEach(f => {
      const coords = f.geometry.coordinates;
      const props = f.properties;
      const marker = L.marker([coords[1], coords[0]]).addTo(map);
      marker.bindPopup(`
        <strong>Location:</strong> ${props.location}<br/>
        <strong>PPV:</strong> ${props.ppv}<br/>
        <strong>PSPL:</strong> ${props.pspl}
      `);
    });
  });