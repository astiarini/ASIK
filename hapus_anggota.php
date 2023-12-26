<?php
  // periksa apakah user sudah login, cek kehadiran session name
  // jika tidak ada, redirect ke login.php
  // session_start();
  // if (!isset($_SESSION["nama"])) {
  //    header("Location: login.php");
  // }

  // buka koneksi dengan MySQL
  include("connection.php");

  // cek apakah form telah di submit (untuk menghapus data)
  if (isset($_POST["submit"])) {
    // form telah disubmit, proses data

    // ambil nilai id_anggota
    $id_kode = htmlentities(strip_tags(trim($_POST["id_kode"])));
    // filter data
    $id_kode = mysqli_real_escape_string($link,$id_kode);

    //jalankan query DELETE
    $query = "DELETE FROM koding WHERE id_kode='$id_kode' ";
    $hasil_query = mysqli_query($link, $query);

    //periksa query, tampilkan pesan kesalahan jika gagal
    if($hasil_query) {
      // DELETE berhasil, redirect ke tampil_anggota.php + pesan
        $pesan = "Anggota UMKM dengan id_anggota = \"<b>$id_anggota</b>\" sudah berhasil di hapus";
      $pesan = urlencode($pesan);
        header("Location: tampil_anggota.php?pesan={$pesan}");
    }
    else {
      die ("Query gagal dijalankan: ".mysqli_errno($link).
           " - ".mysqli_error($link));
    }
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sistem Informasi UKM</title>
  <link href="style3.css" rel="stylesheet" >
  <link rel="icon" href="favicon.png" type="image/png" >
</head>
<body>
<div class="container">
<div id="header">
  <h1 id="logo">Sistem Informasi <span>Usaha Kecil Menengah</span></h1>
  <p id="tanggal"><?php echo date("d M Y"); ?></p>
</div>
<hr>
  <nav>
  <ul>
    <li><a href="index1.php">Kembali</a></li>
    <li><a href="tampil_anggota.php">Tampil</a></li>
    <li><a href="tambah_anggota.php">Tambah</a>
    <li><a href="edit_anggota.php">Edit</a>
    <li><a href="hapus_anggota.php">Hapus</a></li>
    <li><a href="tampil2.php">Cari Lokasi</a></li>
    <li><a href="logout.php">Logout</a>
  </ul>
  </nav>
  <!-- <form id="search" action="tampil_anggota.php" method="get">
    <p>
      <label for="id_anggota">Nama : </label>
      <input type="text" name="nama" id="nama" placeholder="search..." >
      <input type="submit" name="submit" value="Search">
    </p>
  </form> -->
<h2>Hapus Data Anggota UMKM</h2>
<?php
  // tampilkan pesan jika ada
  if ((isset($_GET["pesan"]))) {
      echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";
  }
?>
 <table border="1">
  <tr>
  <th>Id Kode</th>
  <th>Diagnosa</th>
  <th>Kode</th>
  </tr>
  <?php
  // buat query untuk menampilkan seluruh data tabel anggota
  $query = "SELECT * FROM koding ORDER BY diagnosa ASC";
  $result = mysqli_query($link, $query);

  if(!$result){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }

  //buat perulangan untuk element tabel dari data anggota UMKM
  while($data = mysqli_fetch_assoc($result))
  {
    echo "<tr>";
    echo "<td>$data[id_kode]</td>";
    echo "<td>$data[diagnosa]</td>";
    echo "<td>$data[kode]</td>";
    echo "<td>";
    ?>
      <form action="hapus_anggota.php" method="post" >
      <input type="hidden" name="id_kode" value="<?php echo "$data[id_kode]"; ?>" >
      <input type="submit" name="submit" value="Hapus" >
      </form>
    <?php
    echo "</td>";
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
