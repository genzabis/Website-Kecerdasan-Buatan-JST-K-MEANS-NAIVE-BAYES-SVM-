<?php
// Ensure no output before headers
ob_start();
include 'koneksi.php';
include 'naive_bayes.php';

// Initialize status message
$status = "";

try {
    // Process classification
    $query = mysqli_query($conn, "SELECT * FROM data_uji");

    if (!$query) {
        throw new Exception("Error fetching data: " . mysqli_error($conn));
    }

    $processed = 0;
    while ($row = mysqli_fetch_assoc($query)) {
        $input = [
            'penghasilan' => $row['penghasilan'],
            'tanggungan'  => $row['tanggungan'],
            'pekerjaan'   => $row['pekerjaan'],
            'kepemilikan' => $row['kepemilikan']
        ];

        $hasil = klasifikasi($conn, $input);
        $update = mysqli_query($conn, "UPDATE data_uji SET hasil='$hasil' WHERE id={$row['id']}");

        if (!$update) {
            throw new Exception("Error updating record: " . mysqli_error($conn));
        }

        $processed++;
    }

    $status = "Klasifikasi selesai. $processed data telah diproses.";
} catch (Exception $e) {
    $status = "Error: " . $e->getMessage();
}

// Clear buffer and set proper headers
ob_end_clean();
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Klasifikasi</title>
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
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 245, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .alert {
            background: rgba(6, 255, 165, 0.1);
            border: 1px solid rgba(6, 255, 165, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-error {
            background: rgba(255, 0, 110, 0.1);
            border-color: rgba(255, 0, 110, 0.3);
        }
    </style>
</head>

<body>
    <!-- Cosmic Background -->
    <div class="cosmic-bg"></div>
    <div class="stars" id="starsContainer"></div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="card p-8 max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                    <i class="fas fa-check-circle mr-2"></i>Proses Klasifikasi
                </h1>
            </div>

            <div class="<?= strpos($status, 'Error') !== false ? 'alert-error' : 'alert' ?>">
                <span><?= htmlspecialchars($status) ?></span>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="hasil.php" class="btn">
                    <i class="fas fa-table mr-2"></i> Lihat Hasil
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <script>
        // Create stars
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('starsContainer');
            const starCount = 100;

            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.className = 'star';

                const x = Math.random() * 100;
                const y = Math.random() * 100;
                const size = Math.random() * 2 + 1;

                star.style.left = `${x}%`;
                star.style.top = `${y}%`;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.animationDelay = `${Math.random() * 3}s`;

                container.appendChild(star);
            }
        });
    </script>
</body>

</html>