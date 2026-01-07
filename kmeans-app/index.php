<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Means Clustering</title>
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

        .btn-warning {
            background: linear-gradient(45deg, #ffbe0b, #fb5607);
        }

        .btn-warning:hover {
            box-shadow: 0 5px 15px rgba(251, 86, 7, 0.4);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 245, 255, 0.25);
            color: white;
        }

        .info-icon {
            color: var(--primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .info-icon:hover {
            transform: scale(1.1);
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

            .btn:hover,
            .btn-warning:hover {
                transform: none;
                box-shadow: none;
            }

            .cosmic-bg {
                animation: none;
            }

            .stars .star {
                animation: none;
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
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <header class="text-center mb-8 sm:mb-12">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                        <i class="fas fa-project-diagram mr-2"></i>K-Means Clustering
                    </span>
                </h1>
                <p class="text-sm sm:text-lg text-gray-300 max-w-2xl mx-auto">
                    Algoritma pengelompokan data tanpa pengawasan (unsupervised learning)
                </p>
            </header>

            <!-- Main Card -->
            <div class="card p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-cogs text-purple-400"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold">Penerapan Metode K-Means</h2>
                </div>

                <form method="POST" action="proses_kmeans.php">
                    <div class="mb-6">
                        <label for="k" class="block text-sm sm:text-base font-medium text-gray-300 mb-2">
                            Jumlah Kluster (K)
                        </label>
                        <input type="number" class="form-control w-full"
                            id="k" name="k" required min="1" max="10"
                            placeholder="Contoh: 3">
                        <div class="text-xs sm:text-sm text-gray-400 mt-2">
                            Nilai K menentukan berapa banyak kelompok data yang akan dibentuk
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
                        <button type="submit" class="btn flex items-center justify-center">
                            <i class="fas fa-cogs mr-2"></i> Proses K-Means
                        </button>
                        <a href="data.php" class="btn-warning btn flex items-center justify-center">
                            <i class="fas fa-database mr-2"></i> Lihat Data
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <footer class="text-center py-6 border-t border-gray-800">
                <p class="text-xs sm:text-sm text-gray-500">© 2025 K-Means - Kecerdasan Buatan</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

            // Initialize tooltips with custom class
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    container: 'body'
                });
            });

            // Validate K value
            document.querySelector('form').addEventListener('submit', function(e) {
                const kValue = document.getElementById('k').value;
                if (kValue < 1 || kValue > 10) {
                    alert('Nilai K harus antara 1 dan 10');
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>