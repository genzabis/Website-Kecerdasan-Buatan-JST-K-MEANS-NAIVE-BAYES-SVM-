<?php
include 'koneksi.php';

function getDistribusi($conn, $atribut, $opsi)
{
    $data = [];
    foreach ($opsi as $val) {
        $q1 = $conn->query("SELECT COUNT(*) AS total FROM data_uji WHERE $atribut='$val' AND hasil='Tinggi'");
        $q2 = $conn->query("SELECT COUNT(*) AS total FROM data_uji WHERE $atribut='$val' AND hasil='Rendah'");
        $data[] = [
            'label' => $val,
            'tinggi' => $q1->fetch_assoc()['total'],
            'rendah' => $q2->fetch_assoc()['total']
        ];
    }
    return $data;
}

$usia_opsi = ['<30', '30-50', '>50'];
$bmi_opsi = ['Normal', 'Pre-Obesitas', 'Obesitas'];
$riwayat_opsi = ['Ya', 'Tidak'];
$aktivitas_opsi = ['Tinggi', 'Sedang', 'Rendah'];

$dist_usia = getDistribusi($conn, 'usia', $usia_opsi);
$dist_bmi = getDistribusi($conn, 'bmi', $bmi_opsi);
$dist_riwayat = getDistribusi($conn, 'riwayat', $riwayat_opsi);
$dist_aktivitas = getDistribusi($conn, 'aktivitas', $aktivitas_opsi);

$q1 = $conn->query("SELECT COUNT(*) AS total FROM data_uji WHERE hasil = 'Tinggi'");
$q2 = $conn->query("SELECT COUNT(*) AS total FROM data_uji WHERE hasil = 'Rendah'");
$tinggi = $q1->fetch_assoc()['total'];
$rendah = $q2->fetch_assoc()['total'];
$total_data = $tinggi + $rendah;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASIL KLASIFIKASI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card {
            background: rgba(15, 23, 42, 0.8);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-row:hover {
            background: rgba(30, 41, 59, 0.5);
        }

        .highlight {
            transition: all 0.2s ease;
        }

        .highlight:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-cyan-400">
                <i class="fas fa-chart-line mr-2"></i>HASIL KLASIFIKASI
            </h1>
            <div class="bg-gray-800 px-3 py-1 rounded-full text-sm">
                Total Data: <span class="font-bold"><?= $total_data ?></span>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="card p-4 highlight">
                <div class="flex items-center">
                    <div class="bg-cyan-900/30 p-3 rounded-full mr-3">
                        <i class="fas fa-arrow-up text-cyan-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Tinggi</p>
                        <p class="text-xl font-bold"><?= $tinggi ?></p>
                        <p class="text-xs text-cyan-400"><?= round(($tinggi / $total_data) * 100, 1) ?>%</p>
                    </div>
                </div>
            </div>

            <div class="card p-4 highlight">
                <div class="flex items-center">
                    <div class="bg-emerald-900/30 p-3 rounded-full mr-3">
                        <i class="fas fa-arrow-down text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Rendah</p>
                        <p class="text-xl font-bold"><?= $rendah ?></p>
                        <p class="text-xs text-emerald-400"><?= round(($rendah / $total_data) * 100, 1) ?>%</p>
                    </div>
                </div>
            </div>

            <div class="card p-4 highlight">
                <div class="flex items-center">
                    <div class="bg-purple-900/30 p-3 rounded-full mr-3">
                        <i class="fas fa-calendar-day text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Update Terakhir</p>
                        <p class="text-xl font-bold"><?= date("M j") ?></p>
                        <p class="text-xs text-purple-400"><?= date("H:i") ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Data Table -->
                <div class="card p-4">
                    <h2 class="text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-table text-cyan-400 mr-2"></i>
                        Data Klasifikasi 
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="p-3 text-left text-sm">Umur</th>
                                    <th class="p-3 text-left text-sm">BMI</th>
                                    <th class="p-3 text-left text-sm">Riwayat Keluarga</th>
                                    <th class="p-3 text-left text-sm">Aktivitas Fisik</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                <?php
                                $q = $conn->query("SELECT * FROM data_uji LIMIT 5");
                                while ($r = $q->fetch_assoc()) {
                                    $resultClass = $r['hasil'] == 'Tinggi' ? 'text-cyan-400' : 'text-emerald-400';
                                    echo "<tr class='table-row'>
                                            <td class='p-3 text-sm'>{$r['usia']}</td>
                                            <td class='p-3 text-sm'>{$r['bmi']}</td>
                                            <td class='p-3 text-sm'>{$r['riwayat']}</td>
                                            <td class='p-3 text-sm font-bold $resultClass'>{$r['hasil']}</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <a href="#" class="text-sm text-cyan-400 hover:underline">Lihat Semua →</a>
                    </div>
                </div>

                <!-- Distribution Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="card p-4">
                        <h3 class="text-md font-semibold mb-3">Berdasarkan Umur</h3>
                        <canvas id="chartUsia" height="200"></canvas>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-md font-semibold mb-3">Berdasarkan BMI</h3>
                        <canvas id="chartBMI" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Main Chart -->
                <div class="card p-4">
                    <h3 class="text-md font-semibold mb-3">Klasifikasi</h3>
                    <canvas id="chartHasil" height="250"></canvas>
                </div>

                <!-- Small Charts -->
                <div class="grid grid-cols-1 gap-4">
                    <div class="card p-4">
                        <h3 class="text-md font-semibold mb-3">Berdasarkan Riwayat Keluarga</h3>
                        <canvas id="chartRiwayat" height="150"></canvas>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-md font-semibold mb-3">Berdasarkan Aktivitas</h3>
                        <canvas id="chartAktivitas" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

    <script>
        // Main Chart
        new Chart(document.getElementById('chartHasil'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Low'],
                datasets: [{
                    data: [<?= $tinggi ?>, <?= $rendah ?>],
                    backgroundColor: ['#06b6d4', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Mini Charts
        function createMiniChart(id, data) {
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels: data.map(d => d.label),
                    datasets: [{
                            label: 'High',
                            data: data.map(d => d.tinggi),
                            backgroundColor: '#06b6d4'
                        },
                        {
                            label: 'Low',
                            data: data.map(d => d.rendah),
                            backgroundColor: '#10b981'
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        createMiniChart('chartUsia', <?= json_encode($dist_usia) ?>);
        createMiniChart('chartBMI', <?= json_encode($dist_bmi) ?>);
        createMiniChart('chartRiwayat', <?= json_encode($dist_riwayat) ?>);
        createMiniChart('chartAktivitas', <?= json_encode($dist_aktivitas) ?>);
    </script>
</body>

</html>