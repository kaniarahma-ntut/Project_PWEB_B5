// Wait for DOM to load since we're injecting scripts via Vite/Blade
document.addEventListener('DOMContentLoaded', function() {
    // Design System Colors mapping to JS variables
    const c_dark = '#1B262C';
    const c_secondary = '#0F4C75';
    const c_primary = '#3282B8';
    const c_light = '#BBE1FA';
    const c_base = '#FFFFFF';

    // Set Default Fonts & Colors for Chart.js
    if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = "'Open Sans', sans-serif";
        Chart.defaults.color = c_secondary;
        Chart.defaults.font.weight = '600';

        // 1. Chart Tren Peminjaman (Line Chart)
        const trendEl = document.getElementById('trendChart');
        if (trendEl) {
            new Chart(trendEl.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: [120, 190, 150, 220, 180, 250],
                        borderColor: c_primary,
                        backgroundColor: 'rgba(50, 130, 184, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: c_base,
                        pointBorderColor: c_primary,
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: c_dark,
                            titleFont: { family: 'Montserrat', size: 13 },
                            bodyFont: { family: 'Open Sans', size: 12 },
                            padding: 10,
                            cornerRadius: 4
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#e2e8f0' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Chart Ketersediaan (Pie/Doughnut Chart)
        const availabilityEl = document.getElementById('availabilityChart');
        if (availabilityEl) {
            new Chart(availabilityEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Sedang Dipinjam'],
                    datasets: [{
                        data: [11608, 842],
                        backgroundColor: [c_primary, c_dark],
                        borderWidth: 2,
                        borderColor: c_base,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%', // Thinner ring
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: { family: 'Montserrat', weight: 'bold' }
                            }
                        },
                        tooltip: {
                            backgroundColor: c_secondary,
                            titleFont: { family: 'Montserrat', size: 13 },
                            bodyFont: { family: 'Open Sans', size: 12 }
                        }
                    }
                }
            });
        }

        // 3. Chart Anggota (Bar Chart)
        const memberEl = document.getElementById('memberChart');
        if (memberEl) {
            new Chart(memberEl.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Mg 1', 'Mg 2', 'Mg 3', 'Mg 4'],
                    datasets: [
                        {
                            label: 'Anggota Baru',
                            data: [12, 19, 8, 15],
                            backgroundColor: c_light,
                            borderRadius: 2
                        },
                        {
                            label: 'Aktif Meminjam',
                            data: [45, 52, 38, 60],
                            backgroundColor: c_primary,
                            borderRadius: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { family: 'Montserrat', weight: 'bold' }
                            }
                        },
                        tooltip: {
                            backgroundColor: c_dark
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#e2e8f0' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    }
});

// Simulasi Role Switcher (Pustakawan vs Admin)
// Mounted globally so the inline onclick triggers can access it
window.setRole = function(role) {
    const btnPustakawan = document.getElementById('btn-pustakawan');
    const btnAdmin = document.getElementById('btn-admin');
    const adminTools = document.getElementById('admin-tools');

    // Reset styles
    btnPustakawan.className = "px-4 py-1.5 text-sm font-bold rounded text-secondary hover:text-primary transition-all";
    btnAdmin.className = "px-4 py-1.5 text-sm font-bold rounded text-secondary hover:text-primary transition-all";

    if (role === 'admin') {
        btnAdmin.className = "px-4 py-1.5 text-sm font-bold rounded bg-base shadow-sm text-primary transition-all";
        adminTools.classList.remove('hidden'); // Show export tools
        adminTools.classList.add('flex');
    } else {
        btnPustakawan.className = "px-4 py-1.5 text-sm font-bold rounded bg-base shadow-sm text-primary transition-all";
        adminTools.classList.add('hidden'); // Hide export tools
        adminTools.classList.remove('flex');
    }
}
