<?php

  $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_ujian4";
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

$sql = "SELECT b.CustomerNumber, a.CustomerName, b.amount AS rata,
    CASE
    WHEN b.amount < 20000 THEN 'Perusahaan Kecil'
    WHEN b.amount >= 20000 AND b.amount <= 50000 THEN 'Perusahaan Sedang'
    WHEN b.amount > 50000 THEN 'Perusahaan Besar' 
    END  AS jenis
    FROM customer AS a
    JOIN excel_payment AS b ON a.customer_key=b.customerNumber
    GROUP BY b.CustomerNumber";

$result = mysqli_query($conn, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Mengirimkan data sebagai respons JSON
header('Content-Type: application/json');
echo json_encode($data);

// Menutup koneksi database
mysqli_close($conn);
?>