<?php 
include 'koneksi.php'; 
$penghasilan = $_POST['penghasilan']; 
$tanggungan = $_POST['tanggungan']; 
$pekerjaan = $_POST['pekerjaan']; 
$kepemilikan = $_POST['kepemilikan']; 
mysqli_query($conn, "INSERT INTO data_uji (penghasilan, tanggungan, pekerjaan, kepemilikan)  
VALUES ('$penghasilan', '$tanggungan', '$pekerjaan', '$kepemilikan')"); 
header("Location: uji.php"); 
?>