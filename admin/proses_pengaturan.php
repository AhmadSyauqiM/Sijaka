<?php
session_start();
require '../koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// Ambil Action dari URL
$act = isset($_GET['act']) ? $_GET['act'] : '';

// 1. PROSES UPDATE PENGATURAN SISTEM (MAINTENANCE)
if ($act == 'system_settings') {
    $app_name = mysqli_real_escape_string($koneksi, $_POST['app_name']);
    
    // Logika Checkbox: Jika dicentang bernilai 1, jika tidak ada (tidak dicentang) bernilai 0
    $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
    
    // Kita asumsikan pengaturan sistem ada di ID 1
    // Pastikan tabel pengaturan_sistem sudah ada (lihat langkah 3 di bawah)
    $query = "UPDATE pengaturan_sistem SET 
              app_name = '$app_name', 
              maintenance_mode = '$maintenance_mode' 
              WHERE id = 1";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Pengaturan sistem berhasil disimpan! Mode Maintenance: " . ($maintenance_mode ? 'AKTIF' : 'NON-AKTIF') . "');
                window.location = 'pengaturan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan pengaturan: " . mysqli_error($koneksi) . "');
                window.location = 'pengaturan.php';
              </script>";
    }
}

// 2. PROSES UPDATE TEMA (Dipanggil via AJAX/Fetch JS)
elseif ($act == 'save_theme') {
    $theme = mysqli_real_escape_string($koneksi, $_POST['theme']);
    // Update kolom theme di id 1
    mysqli_query($koneksi, "UPDATE pengaturan_sistem SET theme = '$theme' WHERE id = 1");
    exit; // Tidak perlu redirect untuk AJAX
}

// 3. PROSES UPDATE KOP SURAT
elseif ($act == 'update_letterhead') {
    $instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);
    $dinas    = mysqli_real_escape_string($koneksi, $_POST['nama_dinas']);
    $alamat   = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telp     = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email_instansi']);
    $web      = mysqli_real_escape_string($koneksi, $_POST['website']);
    $pos      = mysqli_real_escape_string($koneksi, $_POST['kode_pos']);

    // Logika Upload Logo
    $logo_sql = "";
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../assets/";
        // Buat nama file unik
        $file_extension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
        $new_filename = "logo_instansi." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $logo_path = "assets/" . $new_filename;
            $logo_sql = ", logo = '$logo_path'";
        }
    }

    // Cek apakah data sudah ada, jika belum INSERT, jika sudah UPDATE
    $cek = mysqli_query($koneksi, "SELECT id FROM pengaturan_instansi LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        $query = "UPDATE pengaturan_instansi SET 
                  nama_instansi='$instansi', nama_dinas='$dinas', alamat='$alamat', 
                  telepon='$telp', email='$email', website='$web', kode_pos='$pos' 
                  $logo_sql WHERE id=1";
    } else {
        $logo_val = isset($logo_path) ? $logo_path : 'assets/logo_default.png';
        $query = "INSERT INTO pengaturan_instansi VALUES (1, '$instansi', '$dinas', '$alamat', '$telp', '$email', '$web', '$pos', '$logo_val')";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Kop surat berhasil diperbarui!'); window.location='pengaturan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui kop surat.'); window.location='pengaturan.php';</script>";
    }
}

// 4. PROSES GANTI PASSWORD
elseif ($act == 'change_password') {
    $id_user = $_SESSION['id_user'];
    $pass_lama = $_POST['current_password']; // Harus dicek dulu ke DB apakah cocok (belum diimplementasikan enkripsi di contoh ini)
    $pass_baru = $_POST['new_password'];
    $pass_konf = $_POST['confirm_password'];

    if ($pass_baru == $pass_konf) {
        // Query update password (disarankan pakai password_hash di real app)
        $query = "UPDATE users SET password = '$pass_baru' WHERE id = '$id_user'";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Password berhasil diubah! Silakan login ulang.'); window.location='logout.php';</script>";
        }
    } else {
        echo "<script>alert('Konfirmasi password tidak cocok.'); window.location='pengaturan.php';</script>";
    }
}

else {
    // Jika tidak ada action
    header("Location: pengaturan.php");
}
?>