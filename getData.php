<?php
header('Content-Type: application/json'); 
$host = "localhost"; 
$user = "root"; 
$password = ""; 
$database = "tb_cuaca.sql"; 

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel tb_cuaca
$sql = "SELECT * FROM tb_cuaca";
$result = $conn->query($sql);

$data = array();

// Cek apakah ada data yang ditemukan
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Mengembalikan data dalam format JSON dengan JSON_PRETTY_PRINT
echo json_encode($data, JSON_PRETTY_PRINT);

// Tutup koneksi
$conn->close();
?>
