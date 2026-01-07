<?php
session_start();
$data = $_SESSION['data'] ?? [];
$centroids = $_SESSION['centroids'] ?? [];
$k = $_SESSION['k'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil K-Means Clustering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600&display=swap');

        :root {
            --primary: #00f5ff;
            --secondary: #8338ec;
            --accent: #06ffa5;
        }

        /* Cosmic Background */
        .cosmic-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%),
                linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 25%, #16213e 50%, #0f0f23 75%, #000000 100%);
            z-index: -1;
            animation: cosmicShift 20s ease-in-out infinite alternate;
        }

        @keyframes cosmicShift {
            0% {
                transform: scale(1) rotate(0deg);
            }

            100% {
                transform: scale(1.1) rotate(2deg);
            }
        }

        /* Stars */
        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s ease-in-out infinite;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Floating Orbs */
        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            animation: floatOrb 15s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes floatOrb {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(100px, -50px) scale(1.1);
            }

            50% {
                transform: translate(-50px, -100px) scale(0.9);
            }

            75% {
                transform: translate(-100px, 50px) scale(1.05);
            }
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            color: #e0e0e0;
            overflow-x: hidden;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 245, 255, 0.2);
        }

        .btn {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 245, 255, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: rgba(0, 245, 255, 0.1);
            color: var(--primary);
            font-weight: 600;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .cluster-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .floating-orb {
                display: none;
            }

            .card:hover {
                transform: none;
                box-shadow: none;
            }

            .btn:hover {
                transform: none;
                box-shadow: none;
            }

            .cosmic-bg {
                animation: none;
            }

            .stars .star {
                animation: none;
            }

            table {
                font-size: 0.8rem;
            }

            th,
            td {
                padding: 8px 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Cosmic Background -->
    <div class="cosmic-bg"></div>

    <!-- Stars -->
    <div class="stars" id="starsContainer"></div>

    <!-- Floating Orbs - Hidden on mobile -->
    <div class="hidden sm:block floating-orb w-96 h-96 bg-gradient-to-br from-cyan-400/20 to-blue-600/20" style="top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="hidden sm:block floating-orb w-80 h-80 bg-gradient-to-br from-pink-400/20 to-red-600/20" style="top: 60%; right: 10%; animation-delay: 5s;"></div>
    <div class="hidden sm:block floating-orb w-72 h-72 bg-gradient-to-br from-purple-400/20 to-indigo-600/20" style="bottom: 20%; left: 20%; animation-delay: 10s;"></div>

    <div class="min-h-screen relative z-10 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold mb-2">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                        <i class="fas fa-project-diagram mr-2"></i>Hasil K-Means Clustering
                    </span>
                </h1>
                <div class="text-xl text-gray-300 mb-4">
                    <span class="bg-purple-900/50 px-3 py-1 rounded-full">
                        K = <?= $k ?>
                    </span>
                </div>
                <a href="index.php" class="btn inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </header>

            <!-- Results Card -->
            <div class="card p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-pie text-purple-400"></i>
                    </div>
                    <h2 class="text-xl font-bold">Visualisasi Klaster</h2>
                </div>

                <div class="h-96 mb-6">
                    <canvas id="chartKlaster"></canvas>
                </div>

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-table text-blue-400"></i>
                    </div>
                    <h2 class="text-xl font-bold">Data Klaster</h2>
                </div>

                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fitur 1</th>
                                <th>Fitur 2</th>
                                <th>Klaster</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $d): ?>
                                <tr>
                                    <td><?= $d['id'] ?></td>
                                    <td><?= number_format($d['fitur1'], 2) ?></td>
                                    <td><?= number_format($d['fitur2'], 2) ?></td>
                                    <td>
                                        <span class="cluster-badge" style="background: <?= getClusterColor($d['cluster']); ?>">
                                            Klaster <?= $d['cluster'] + 1 ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center py-6 border-t border-gray-800">
                <p class="text-sm text-gray-500">© 2023 K-Means Clustering - Kecerdasan Buatan</p>
            </footer>
        </div>
    </div>

    <script>
        // Create stars
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('starsContainer');
            const starCount = window.innerWidth < 640 ? 50 : 100;

            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.className = 'star';

                const x = Math.random() * 100;
                const y = Math.random() * 100;
                const size = Math.random() * (window.innerWidth < 640 ? 1 : 2) + 1;

                star.style.left = `${x}%`;
                star.style.top = `${y}%`;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;

                if (window.innerWidth >= 640) {
                    star.style.animation = `twinkle ${3 + Math.random() * 2}s ease-in-out infinite`;
                    star.style.animationDelay = `${Math.random() * 3}s`;
                }

                container.appendChild(star);
            }

            // Initialize chart
            const ctx = document.getElementById('chartKlaster');
            const dataPoints = <?= json_encode($data) ?>;
            const centroids = <?= json_encode($centroids) ?>;

            const clusterColors = [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
                'rgba(83, 102, 255, 0.7)',
                'rgba(255, 99, 255, 0.7)',
                'rgba(99, 255, 132, 0.7)'
            ];

            const centroidColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(83, 102, 255, 1)',
                'rgba(255, 99, 255, 1)',
                'rgba(99, 255, 132, 1)'
            ];

            // Create datasets for clusters
            const clusterDatasets = [];
            for (let i = 0; i < <?= $k ?>; i++) {
                const clusterData = dataPoints.filter(d => d.cluster === i);
                clusterDatasets.push({
                    label: 'Klaster ' + (i + 1),
                    data: clusterData.map(d => ({
                        x: d.fitur1,
                        y: d.fitur2
                    })),
                    backgroundColor: clusterColors[i % clusterColors.length],
                    pointRadius: 6,
                    pointHoverRadius: 8
                });
            }

            // Add centroids if available
            if (centroids.length > 0) {
                clusterDatasets.push({
                    label: 'Pusat Klaster',
                    data: centroids.map((c, i) => ({
                        x: c[0],
                        y: c[1]
                    })),
                    backgroundColor: centroidColors.slice(0, centroids.length),
                    pointRadius: 10,
                    pointHoverRadius: 12,
                    pointStyle: 'crossRot'
                });
            }

            new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: clusterDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Fitur 1',
                                color: '#e0e0e0'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#e0e0e0'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Fitur 2',
                                color: '#e0e0e0'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#e0e0e0'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#e0e0e0',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Klaster')) {
                                        label += ': (' + context.parsed.x.toFixed(2) + ', ' + context.parsed.y.toFixed(2) + ')';
                                    } else if (label === 'Pusat Klaster') {
                                        label = 'Pusat ' + (context.dataIndex + 1) + ': (' + context.parsed.x.toFixed(2) + ', ' + context.parsed.y.toFixed(2) + ')';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>

<?php
function getClusterColor($clusterIndex)
{
    $colors = [
        '#FF6384', // red
        '#36A2EB', // blue
        '#FFCE56', // yellow
        '#4BC0C0', // teal
        '#9966FF', // purple
        '#FF9F40', // orange
        '#C7C7C7', // gray
        '#5366FF', // blue
        '#FF63FF', // pink
        '#63FF84'  // green
    ];
    return $colors[$clusterIndex % count($colors)];
}
?>