<?php
function klasifikasiSVM($usia, $bmi, $riwayat, $aktivitas)
{
    $score = 0;

    // Bobot fitur (disederhanakan, bisa diganti)
    $bobot = [
        'usia' => ['<30' => 0, '30-50' => 1, '>50' => 2],
        'bmi' => ['Normal' => 0, 'Pre-Obesitas' => 1, 'Obesitas' => 2],
        'riwayat' => ['Tidak' => 0, 'Ya' => 2],
        'aktivitas' => ['Tinggi' => 0, 'Sedang' => 1, 'Rendah' => 2]
    ];

    // Hitung score total
    $score += $bobot['usia'][$usia];
    $score += $bobot['bmi'][$bmi];
    $score += $bobot['riwayat'][$riwayat];
    $score += $bobot['aktivitas'][$aktivitas];

    // Threshold sederhana
    return ($score >= 5) ? "Tinggi" : "Rendah";
}
