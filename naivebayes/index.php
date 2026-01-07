<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Klasifikasi</title>
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
            display: inline-flex;
            align-items: center;
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

    <!-- Stars -->
    <div class="stars" id="starsContainer"></div>

    <div class="min-h-screen relative z-10 py-8 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="card p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                        <i class="fas fa-chart-pie mr-2"></i>Form Klasifikasi
                    </h2>
                    <p class="text-gray-400 mt-2">Isi data berikut untuk proses klasifikasi</p>
                </div>

                <form action="simpan_uji.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-money-bill-wave mr-2"></i>Penghasilan
                        </label>
                        <select name="penghasilan" class="form-control" required>
                            <option value="" disabled selected>Pilih Penghasilan</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users mr-2"></i>Tanggungan
                        </label>
                        <select name="tanggungan" class="form-control" required>
                            <option value="" disabled selected>Pilih Jumlah Tanggungan</option>
                            <option value="Sedikit">Sedikit</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Banyak">Banyak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-briefcase mr-2"></i>Pekerjaan
                        </label>
                        <select name="pekerjaan" class="form-control" required>
                            <option value="" disabled selected>Pilih Status Pekerjaan</option>
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-home mr-2"></i>Kepemilikan
                        </label>
                        <select name="kepemilikan" class="form-control" required>
                            <option value="" disabled selected>Pilih Status Kepemilikan</option>
                            <option value="Sewa">Sewa</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                        </select>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn w-full">
                            <i class="fas fa-save mr-2"></i> Simpan dan Klasifikasikan
                        </button>
                    </div>
                </form>
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