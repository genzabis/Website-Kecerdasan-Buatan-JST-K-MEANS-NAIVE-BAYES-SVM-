<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_kmeans";

$conn = new mysqli($host, $user, $pass, $db, 3306);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
