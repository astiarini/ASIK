<?php

  // buka koneksi dengan MySQL
     include("connection.php");
  
  // ambil pesan jika ada  
  if (isset($_GET["pesan"])) {
      $pesan = $_GET["pesan"];
  }
     
  // cek apakah form telah di submit
  // berasal dari form pencairan, siapkan query 
  if (isset($_GET["submit"])) {
    // ambil nilai nama
    $diagnosa = htmlentities(strip_tags(trim($_GET["diagnosa"])));
    // filter untuk $nama untuk mencegah sql injection
    $diagnosa = mysqli_real_escape_string($link,$diagnosa);    
    // buat query pencarian nama
    $query  = "SELECT * FROM koding WHERE diagnosa LIKE '%$diagnosa%' ";
    $query .= "ORDER BY diagnosa ASC";    
    // buat pesan
    $pesan = "Hasil pencarian untuk diagnosa <b>\"$diagnosa\" </b>:";
  } else {
      // bukan dari form pencairan
      // siapkan query untuk menampilkan seluruh data dari tabel anggota
      $query = "SELECT * FROM koding ORDER BY diagnosa ASC";      
    }

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>ASIK Kusuma</title>
  <link href="style3.css" rel="stylesheet" >
  <link rel="icon" href="favicon.png" type="image/png" >
</head>
<body>
<div class="container">
<div id="header">
  <h1 id="logo">Aplikasi Sistem Informasi Koding<span> Kusuma</span></h1>
  <p id="tanggal"><?php echo date("d M Y"); ?></p>
</div>

<hr>
  <nav>
  <ul>
    <li><a href="index1.php">Kembali</a></li>
    <li><a href="tambah_koding.php">Tambah</a>
    <li><a href="edit_anggota.php">Edit</a>
    <li><a href="hapus_anggota.php">Hapus</a></li>
    <li><a href="tampil2.php">Cari Lokasi</a></li>
    <li><a href="logout.php">Logout</a>
  </ul>
  </nav>

  <!-- tabel pencarian -->
  <form id="search" action="tampil_anggota.php" method="get">
    <p>
      <label for="id_kode">Diagnosa : </label> 
      <input type="text" name="diagnosa" id="diagnosa" placeholder="search..." >
      <input type="submit" name="submit" value="Search">
    </p> 
  </form>


  
<h2>Daftar Diagnosa dan Kode ICD 10</h2>
<?php
  // tampilkan pesan jika ada
  if (isset($pesan)) {
      echo "<div class=\"pesan\">$pesan</div>";
  }
?>
 <table border="1">
  <tr>
  <th>Id Kode</th>
  <th>Diagnosa</th>
  <th>Kode ICD</th>
  </tr>
  <?php
  // jalankan query
  $result = mysqli_query($link, $query);
  
  if(!$result){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }
  
  //buat perulangan untuk element tabel dari data anggota
  while($data = mysqli_fetch_assoc($result))
  { 
    echo "<tr>";
    echo "<td>$data[id_kode]</td>";
    echo "<td>$data[diagnosa]</td>";
    echo "<td>$data[kode]</td>";
    echo "</tr>";
  }
  
  // bebaskan memory 
  mysqli_free_result($result);
  
  // tutup koneksi dengan database mysql
  mysqli_close($link);
  ?>
  </table>
  <div id="footer">
  Copyright © <?php echo date("Y"); ?> Mahasiswa Universitas Madura| Designed by: Sukron Katsir
  </div>
</div>
</body>
</html>