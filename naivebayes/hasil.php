<?php
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM data_uji");
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Uji Klasifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        body {
            font-family: 'Space Grotesk', sans-serif;
            color: #e0e0e0;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            padding: 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
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

        .btn {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .header-title {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(to right, #00f5ff, #8338ec);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card {
                padding: 1rem;
            }

            th,
            td {
                padding: 8px 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Cosmic Background -->
    <div class="cosmic-bg"></div>
    <div class="stars" id="starsContainer"></div>

    <div class="container">
        <div class="card">
            <h1 class="header-title">
                <i class="fas fa-table mr-2"></i>Data Uji Klasifikasi
            </h1>
            <a href="index.php" class="btn">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-money-bill-wave mr-2"></i>Penghasilan</th>
                            <th><i class="fas fa-users mr-2"></i>Tanggungan</th>
                            <th><i class="fas fa-briefcase mr-2"></i>Pekerjaan</th>
                            <th><i class="fas fa-home mr-2"></i>Kepemilikan</th>
                            <th><i class="fas fa-chart-pie mr-2"></i>Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['penghasilan']) ?></td>
                                <td><?= htmlspecialchars($row['tanggungan']) ?></td>
                                <td><?= htmlspecialchars($row['pekerjaan']) ?></td>
                                <td><?= htmlspecialchars($row['kepemilikan']) ?></td>
                                <td><?= htmlspecialchars($row['hasil']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Create stars
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('starsContainer');
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = `${Math.random() * 100}%`;
                star.style.top = `${Math.random() * 100}%`;
                star.style.width = `${Math.random() * 3}px`;
                star.style.height = star.style.width;
                star.style.animationDelay = `${Math.random() * 5}s`;
                container.appendChild(star);
            }
        });
    </script>
</body>

</html>