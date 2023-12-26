<?php
  // periksa apakah user sudah login, cek kehadiran session name
  // jika tidak ada, redirect ke login.php
  // session_start();
  // if (!isset($_SESSION["nama"])) {
  //    header("Location: login.php");
  // }

  // buka koneksi dengan MySQL
  include("connection.php");

  // cek apakah form telah di submit
  if (isset($_POST["submit"])) {
    // form telah disubmit, proses data

    // ambil semua nilai form
    $id_kode        = htmlentities(strip_tags(trim($_POST["id_kode"])));
    $diagnosa       = htmlentities(strip_tags(trim($_POST["diagnosa"])));
    $kode           = htmlentities(strip_tags(trim($_POST["kode"])));
     // siapkan variabel untuk menampung pesan error
     $pesan_error="";

     // cek apakah "id_anggota" sudah diisi atau tidak
     if (empty($id_kode)) {
       $pesan_error .= "<br>id_kode belum diisi <br>";
     }
     // id_anggota harus angka dengan 8 digit
     elseif (!preg_match("/^[0-9]{8}$/",$id_kode) ) {
       $pesan_error .= "Id harus berupa 8 digit angka <br>";
     }
 
     // cek ke database, apakah sudah ada id_anggota id_anggota yang sama
     // filter data $id_anggota
     $id_kode = mysqli_real_escape_string($link,$id_kode);
     $query = "SELECT * FROM koding WHERE id_kode='$id_kode'";
     $hasil_query = mysqli_query($link, $query);
 
     // cek jumlah record (baris), jika ada, $id_anggota tidak bisa diproses
     $jumlah_data = mysqli_num_rows($hasil_query);
      if ($jumlah_data >= 1 ) {
        $pesan_error .= "Id yang sama sudah digunakan <br>";
     }
 
     // cek apakah "nama" sudah diisi atau tidak
     if (empty($diagnosa)) {
       $pesan_error .= "Nama belum di isi <br>";
     }

     // jika tidak ada error, input ke database
     if ($pesan_error === "") {
    
 
       // filter semua data
       $id_kode          = mysqli_real_escape_string($link,$id_kode);
       $diagnosa         = mysqli_real_escape_string($link,$diagnosa );
       $kode             = mysqli_real_escape_string($link,$kode);
       
       //buat dan jalankan query INSERT
      $query = "INSERT INTO koding VALUES ";
      $query .= "('$id_kode', '$diagnosa', '$kode')";
      
      $result = mysqli_query($link, $query);

      //periksa hasil query
      if($result) {
      // INSERT berhasil, redirect ke tampil_anggota.php + pesan
        $pesan = "Diagnosa = \"<b>$diagnosa</b>\" sudah berhasil di tambah";
        $pesan = urlencode($pesan);
        header("Location: tampil_anggota.php?pesan={$pesan}");
      }
      else {
      die ("Query gagal dijalankan: ".mysqli_errno($link).
           " - ".mysqli_error($link));
      }
    }
  }
  else{
     // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error      = "";
    $id_kode          = "";
    $diagnosa         = "";
    $kode             = "";
  }
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sistem Informasi UMKM</title>
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
    <li><a href="edit_anggota.php">Edit</a>
    <li><a href="hapus_anggota.php">Hapus</a></li>
    <li><a href="tampil2.php">Cari Lokasi</a></li>
    <li><a href="logout.php">Logout</a>
  </ul>
  </nav>
  <h2>Tambah Data UMKM</h2>
<?php
  // tampilkan error jika ada
  if ($pesan_error !== "") {
      echo "<div class=\"error\">$pesan_error</div>";
  }
?>
<form id="form_koding" action="tambah_koding.php" method="post">
<fieldset>
<legend>Anggota Baru</legend>
  <p>
    <label for="id_anggota">Id Kode : </label>
    <input type="text" name="id_kode" id="id_kode" value="<?php echo $id_kode ?>"
    placeholder="Contoh: 12345678">
    (8 digit angka)
  </p>
  <p>
    <label for="diagnosa">Diagnosa : </label>
    <input type="text" name="diagnosa" id="diagnosa" value="<?php echo $diagnosa ?>">
  </p>
  <p>
    <label for="kode">Kode ICD 10 : </label>
    <input type="text" name="kode" id="kode"
    value="<?php echo $kode ?>">
  </p>
  </fieldset>
  <br>
  <p>
    <input type="submit" name="submit" value="Tambah Data">
  </p>
</form>

  <div id="footer">
  Copyright © <?php echo date("Y"); ?> Mahasiswa Universitas Madura| Designed by: Sukron Katsir
  </div>

</div>
</body>
</html>
<?php
  // tutup koneksi dengan database mysql
  mysqli_close($link);
?>
