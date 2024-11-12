<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "tb_cuaca.sql"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT id, suhu, humid, lux, ts FROM tb_cuaca";
$result = $conn->query($sql);

// Array untuk menyimpan data
$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Ambil bulan dan tahun dari kolom ts
        $ts = strtotime($row["ts"]);
        $bulan_tahun = date("m-Y", $ts);

        $data[] = [
            "id" => $row["id"],
            "suhu" => $row["suhu"],
            "humid" => $row["humid"],
            "lux" => $row["lux"],
            "ts" => $bulan_tahun // hanya bulan dan tahun
        ];
    }
}

// Output dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Tutup koneksi
$conn->close();
?>