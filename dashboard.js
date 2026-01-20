document.addEventListener('DOMContentLoaded', function() {
    // 1. MAP
    const map = L.map('portMap', { zoomControl: false }).setView([10.7578, 106.7856], 14);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
    const icon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/870/870107.png', iconSize: [30, 30] });
    L.marker([10.7580, 106.7900], {icon: icon}).addTo(map).bindPopup("<b>MAERSK HANOI</b>");
    L.marker([10.7550, 106.7800], {icon: icon}).addTo(map).bindPopup("<b>CMA CGM</b>");

    // 2. CHARTS CONFIG
    Chart.defaults.color = '#64748b'; Chart.defaults.borderColor = '#334155';
    
    // Gradient Helper
    const gradient = (ctx, c1, c2) => {
        let g = ctx.createLinearGradient(0,0,0,300); g.addColorStop(0, c1); g.addColorStop(1, c2); return g;
    };

    // Chart 1: Berth
    const ctx1 = document.getElementById('berthChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['0h','4h','8h','12h','16h','20h'],
            datasets: [{
                data: [45, 50, 75, 88, 60, 55],
                borderColor: '#ef4444', backgroundColor: gradient(ctx1, 'rgba(239,68,68,0.5)', 'rgba(239,68,68,0)'),
                fill: true, tension: 0.4, pointRadius: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: {legend:{display:false}}, scales:{x:{display:false}} }
    });

    // Chart 2: Gate
    const ctx2 = document.getElementById('gateChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['T2','T3','T4','T5','T6','T7','CN'],
            datasets: [{ data: [120, 150, 110, 170, 140, 90, 80], backgroundColor: '#3b82f6', borderRadius: 4 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: {legend:{display:false}}, scales:{x:{display:false}, y:{display:false}} }
    });
});