<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Diabetes</title>
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

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 245, 255, 0.2);
        }

        .btn {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 245, 255, 0.4);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 245, 255, 0.25);
            color: white;
            outline: none;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        select option {
            background-color: rgba(0, 0, 0, 0.8);
            color: #ffffff;
        }


        /* Mobile optimizations */
        @media (max-width: 640px) {
            .card {
                padding: 1.5rem;
            }

            .card:hover {
                transform: none;
                box-shadow: none;
            }

            .btn:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <!-- Cosmic Background -->
    <div class="cosmic-bg"></div>
    <div class="stars" id="starsContainer"></div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="card p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                    <i class="fas fa-heartbeat mr-2"></i>Prediksi Diabetes
                </h1>
                <p class="text-gray-400 mt-2">Isi data berikut untuk prediksi risiko diabetes</p>
            </div>

            <form method="POST" action="simpan_uji.php">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user-clock mr-2"></i>Usia
                    </label>
                    <select name="usia" class="form-control" required>
                        <option value="" disabled selected>Pilih Rentang Usia</option>
                        <option value="<30">
                            <30 Tahun</option>
                        <option value="30-50">30-50 Tahun</option>
                        <option value=">50">>50 Tahun</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-weight mr-2"></i>BMI
                    </label>
                    <select name="bmi" class="form-control" required>
                        <option value="" disabled selected>Pilih Kategori BMI</option>
                        <option value="Normal">Normal</option>
                        <option value="Pre-Obesitas">Pre-Obesitas</option>
                        <option value="Obesitas">Obesitas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-family mr-2"></i>Riwayat Keluarga
                    </label>
                    <select name="riwayat" class="form-control" required>
                        <option value="" disabled selected>Pilih Riwayat Keluarga</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-running mr-2"></i>Aktivitas Fisik
                    </label>
                    <select name="aktivitas" class="form-control" required>
                        <option value="" disabled selected>Pilih Tingkat Aktivitas</option>
                        <option value="Tinggi">Tinggi</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Rendah">Rendah</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn w-full">
                        <i class="fas fa-chart-bar mr-2"></i> Klasifikasi
                    </button>
                </div>
            </form>
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