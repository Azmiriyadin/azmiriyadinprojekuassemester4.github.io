<?php
include "koneksi.php"
?>
<!DOCTYPE html>
<html>
<head>
  <title>UAS Warehouse SMT 4</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      border-block-color: black;
    }
    th, td {
      text-align: left;
      padding: 8px;
    }
    th {
      background-color: #B0C4DE;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

</head>
<body>
  <h2>UAS Warehouse</h2>

  <table>
  <tr>
      <th>No</th>
      <th>CustomerNumber</th>
      <th>Customer Name</th>
      <th>Rata Rata</th>
      <th>Jenis</th>
    </tr>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_ujian4";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
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

 


$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $previousCustomer = ""; 
      $count = 1; 
      $rowspanCount = 0; 
      while ($row = $result->fetch_assoc()) {
        if ($row["CustomerNumber"] != $previousCustomer) {
          $rowspanCount = 1;
          echo "<tr>";
          echo "<td>" . $count . "</td>";
          echo "<td rowspan='1'>" . $row["CustomerNumber"] . "</td>";
          echo "<td rowspan='1'>" . $row["CustomerName"] . "</td>";
          echo "<td rowspan='1'>" . $row["rata"] . "</td>";
          echo "<td rowspan='1'>" . $row["jenis"] . "</td>";
          echo "</tr>";
          $previousCustomer = $row["CustomerName"];
          $count++;
      }else{
        $rowspanCount++;
          echo "<tr>";
          echo "<td></td>";
          echo "<td>" . $row["CustomerNumber"] . "</td>";
          echo "<td>" . $row["CustomerName"] . "</td>";
          echo "<td>" . $row["rata"] . "</td>";
          echo "<td>" . $row["jenis"] . "</td>";
          echo "</tr>";
      }    
    }
  }else {
    echo "<tr><td colspan='7'>No payment details found</td></tr>";
  }
  $conn->close();
    ?>
  </table>

  <?php
  $sql = "SELECT b.CustomerNumber, a.CustomerName, b.amount AS rata,
            CASE
            WHEN b.amount < 20000 THEN 'Perusahaan Kecil'
            WHEN b.amount >= 20000 AND b.amount <= 50000 THEN 'Perusahaan Sedang'
            WHEN b.amount > 50000 THEN 'Perusahaan Besar' 
            END  AS jenis
            FROM customer AS a
            JOIN excel_payment AS b ON a.customer_key=b.customerNumber
            GROUP BY b.CustomerNumber";
  $result = mysqli_query($koneksi_mysqlp, $sql);
  $data = array();
  while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }
  ?>

<br>
<br>
<br>





<canvas id="myChart"></canvas>

<script>
  var data = <?php echo json_encode($data); ?>; // Mengambil data dari variabel $data dalam PHP

  // Menghitung jumlah setiap jenis
  var counts = {};
  data.forEach(function(item) {
    counts[item.jenis] = (counts[item.jenis] || 0) + 1;
  });

  var jenis = Object.keys(counts);
  var datasets = [{
    data: jenis.map((jenis) => counts[jenis]), // Menggunakan jumlah setiap jenis
    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'] // Contoh warna untuk setiap bagian
  }];

  var chartData = {
    labels: jenis,
    datasets: datasets
  };

  // Konfigurasi opsi chart
  var options = {
    responsive: true
    
  };

  // Membuat pie chart
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'pie',
    data: chartData,
    options: options
  });
  
</script>

</body>
  </html>