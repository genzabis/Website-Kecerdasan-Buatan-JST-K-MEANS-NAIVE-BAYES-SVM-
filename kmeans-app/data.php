<?php
include 'koneksi.php';

// Inisialisasi variabel
$fitur1 = $fitur2 = "";
$edit_mode = false;

// Tambah Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $fitur1 = $_POST['fitur1'];
    $fitur2 = $_POST['fitur2'];
    $stmt = $conn->prepare("INSERT INTO data_unlabeled (fitur1, fitur2) VALUES (?, ?)");
    $stmt->bind_param("dd", $fitur1, $fitur2);
    $stmt->execute();
    $stmt->close();
    header("Location: data.php");
    exit();
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM data_unlabeled WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: data.php");
    exit();
}

// Ambil Data untuk Edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM data_unlabeled WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_edit = $result->fetch_assoc();
    $fitur1 = $data_edit['fitur1'];
    $fitur2 = $data_edit['fitur2'];
    $stmt->close();
}

// Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $fitur1 = $_POST['fitur1'];
    $fitur2 = $_POST['fitur2'];
    $stmt = $conn->prepare("UPDATE data_unlabeled SET fitur1 = ?, fitur2 = ? WHERE id = ?");
    $stmt->bind_param("ddi", $fitur1, $fitur2, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: data.php");
    exit();
}

// Ambil Semua Data
$result = $conn->query("SELECT * FROM data_unlabeled ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Unlabeled</title>
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

        .btn-danger {
            background: linear-gradient(45deg, #ff0b3b, #d90429);
        }

        .btn-danger:hover {
            box-shadow: 0 5px 15px rgba(217, 4, 41, 0.4);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 245, 255, 0.25);
            color: white;
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
            .btn-warning:hover,
            .btn-danger:hover {
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

            .form-row {
                flex-direction: column;
            }

            .form-control {
                width: 100%;
                margin-bottom: 0.5rem;
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
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <header class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold mb-4">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                        <i class="fas fa-database mr-2"></i>Manajemen Data Unlabeled
                    </span>
                </h1>
                <a href="index.php" class="btn inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </header>

            <!-- Form Card -->
            <div class="card p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-plus-circle text-blue-400"></i>
                    </div>
                    <h2 class="text-xl font-bold">
                        <?= $edit_mode ? 'Edit Data' : 'Tambah Data Baru' ?>
                    </h2>
                </div>

                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="fitur1" class="block text-sm font-medium text-gray-300 mb-2">Fitur 1</label>
                            <input type="number" step="any" class="form-control w-full"
                                id="fitur1" name="fitur1" required
                                value="<?= htmlspecialchars($fitur1) ?>">
                        </div>
                        <div>
                            <label for="fitur2" class="block text-sm font-medium text-gray-300 mb-2">Fitur 2</label>
                            <input type="number" step="any" class="form-control w-full"
                                id="fitur2" name="fitur2" required
                                value="<?= htmlspecialchars($fitur2) ?>">
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if ($edit_mode): ?>
                                <input type="hidden" name="id" value="<?= $_GET['edit'] ?>">
                                <button type="submit" name="update" class="btn-warning btn flex items-center">
                                    <i class="fas fa-save mr-2"></i> Update
                                </button>
                                <a href="data.php" class="btn flex items-center" style="background: rgba(255, 255, 255, 0.1);">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                            <?php else: ?>
                                <button type="submit" name="tambah" class="btn flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Tambah
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table Card -->
            <div class="card p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-table text-purple-400"></i>
                    </div>
                    <h2 class="text-xl font-bold">Daftar Data Unlabeled</h2>
                </div>

                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fitur 1</th>
                                <th>Fitur 2</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= number_format($row['fitur1'], 4) ?></td>
                                    <td><?= number_format($row['fitur2'], 4) ?></td>
                                    <td class="flex gap-2">
                                        <a href="data.php?edit=<?= $row['id'] ?>" class="btn-warning btn btn-sm flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <a href="data.php?hapus=<?= $row['id'] ?>"
                                            class="btn-danger btn btn-sm flex items-center"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center py-6 border-t border-gray-800 mt-8">
                <p class="text-sm text-gray-500">© 2023 Manajemen Data - Kecerdasan Buatan</p>
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
        });
    </script>
</body>

</html>