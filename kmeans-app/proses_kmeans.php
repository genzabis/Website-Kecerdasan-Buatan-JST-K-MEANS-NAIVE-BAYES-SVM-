<?php
include 'koneksi.php';

function euclidean_distance($a, $b) {
    return sqrt(pow($a['fitur1'] - $b['fitur1'], 2) + pow($a['fitur2'] - $b['fitur2'], 2));
}

$k = $_POST['k'] ?? 3;

// Ambil data dari database
$query = "SELECT * FROM data_unlabeled";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'fitur1' => $row['fitur1'],
        'fitur2' => $row['fitur2'],
        'cluster' => null
    ];
}

// Inisialisasi centroid secara acak
$centroids = [];
$keys = array_rand($data, $k);
foreach ($keys as $key) {
    $centroids[] = [
        'fitur1' => $data[$key]['fitur1'],
        'fitur2' => $data[$key]['fitur2']
    ];
}

$max_iterations = 100;
for ($i = 0; $i < $max_iterations; $i++) {
    // Assign data ke centroid terdekat
    foreach ($data as &$point) {
        $min_distance = null;
        $cluster = null;
        foreach ($centroids as $idx => $centroid) {
            $distance = euclidean_distance($point, $centroid);
            if (is_null($min_distance) || $distance < $min_distance) {
                $min_distance = $distance;
                $cluster = $idx;
            }
        }
        $point['cluster'] = $cluster;
    }
    unset($point);

    // Hitung centroid baru
    $new_centroids = [];
    for ($j = 0; $j < $k; $j++) {
        $cluster_points = array_filter($data, fn($d) => $d['cluster'] === $j);
        $count = count($cluster_points);
        if ($count > 0) {
            $sum_fitur1 = array_sum(array_column($cluster_points, 'fitur1'));
            $sum_fitur2 = array_sum(array_column($cluster_points, 'fitur2'));
            $new_centroids[] = [
                'fitur1' => $sum_fitur1 / $count,
                'fitur2' => $sum_fitur2 / $count
            ];
        } else {
            // Jika klaster kosong, inisialisasi ulang centroid secara acak
            $random_point = $data[array_rand($data)];
            $new_centroids[] = [
                'fitur1' => $random_point['fitur1'],
                'fitur2' => $random_point['fitur2']
            ];
        }
    }

    // Cek konvergensi
    $converged = true;
    for ($j = 0; $j < $k; $j++) {
        if ($centroids[$j]['fitur1'] != $new_centroids[$j]['fitur1'] ||
            $centroids[$j]['fitur2'] != $new_centroids[$j]['fitur2']) {
            $converged = false;
            break;
        }
    }

    $centroids = $new_centroids;

    if ($converged) {
        break;
    }
}

// Simpan hasil ke sesi
session_start();
$_SESSION['data'] = $data;
$_SESSION['centroids'] = $centroids;
$_SESSION['k'] = $k;

header("Location: hasil.php");
