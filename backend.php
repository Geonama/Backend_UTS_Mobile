<?php

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "tb_cuaca.sql"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT MAX(suhu) AS suhu_max, MIN(suhu) AS suhu_min, AVG(suhu) AS suhu_avg FROM tb_cuaca";
$result = $conn->query($sql);

$data_output = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data_output['suhu_max'] = $row['suhu_max'];
    $data_output['suhu_min'] = $row['suhu_min'];
    $data_output['suhu_avg'] = round($row['suhu_avg'], 2); // Pembulatan rata-rata suhu
}

// Query untuk mendapatkan data dimana suhu dan humid berada di nilai maksimum bersamaan
$sql_max_data = "SELECT id, suhu, humid, lux, ts FROM tb_cuaca 
                 WHERE suhu = {$data_output['suhu_max']} AND humid = (SELECT MAX(humid) FROM tb_cuaca)";
$result_max_data = $conn->query($sql_max_data);

if ($result_max_data->num_rows > 0) {
    $nilai_suhu_max_humid_max = [];
    while ($row = $result_max_data->fetch_assoc()) {
        $nilai_suhu_max_humid_max[] = [
            'id' => $row['id'],
            'suhu' => $row['suhu'],
            'humid' => $row['humid'],
            'lux' => $row['lux'],
            'timestamp' => $row['ts']
        ];
    }
    $data_output['nilai_suhu_max_humid_max'] = $nilai_suhu_max_humid_max;
}

// Query untuk mendapatkan dua data month_year dalam format yang diinginkan
$sql_month_year_max = "SELECT DISTINCT DATE_FORMAT(ts, '%c-%Y') AS month_year FROM tb_cuaca 
                       WHERE suhu = {$data_output['suhu_max']} AND humid = (SELECT MAX(humid) FROM tb_cuaca) 
                       LIMIT 2";
$result_month_year_max = $conn->query($sql_month_year_max);

$month_year_max = [];
if ($result_month_year_max->num_rows > 0) {
    while ($row = $result_month_year_max->fetch_assoc()) {
        $month_year_max[] = ["month_year" => $row['month_year']];
    }
}
$data_output['month_year_max'] = $month_year_max;

// Output dalam format JSON
header('Content-Type: application/json');
echo json_encode($data_output, JSON_PRETTY_PRINT);

// Tutup koneksi
$conn->close();
?>
