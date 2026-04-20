<?php
session_start();
include 'config/database.php';

// Variabel untuk menampung status
$status = "";
$pesan  = "";
$tiket_baru = ""; // Variabel untuk menyimpan tiket yang baru dibuat

if (isset($_POST['submit'])) {
    
    // Cek Koneksi
    if (!isset($koneksi)) {
        $status = "error";
        $pesan = "Error Fatal: Variabel \$koneksi tidak ditemukan.";
    } else {
        
        $nama   = htmlspecialchars($_POST['nama_pelapor']);
        $jenis  = $_POST['jenis_aset'];
        $lokasi = htmlspecialchars($_POST['lokasi_aset']);
        $ket    = htmlspecialchars($_POST['keterangan']);

        // --- PROSES UPLOAD ---
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $uploadedFiles = [];
        $countFiles = count($_FILES['bukti_foto']['name']);

        if ($countFiles > 3) {
            $status = "error";
            $pesan = "Maksimal hanya boleh upload 3 foto!";
        } else {
            // Looping Upload
            for ($i = 0; $i < $countFiles; $i++) {
                $fileName = $_FILES['bukti_foto']['name'][$i];
                $fileTmp  = $_FILES['bukti_foto']['tmp_name'][$i];
                $fileErr  = $_FILES['bukti_foto']['error'][$i];

                if ($fileErr === 0) {
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = date('Ymd') . '_' . uniqid() . '_' . $i . '.' . $ext;
                    $targetPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmp, $targetPath)) {
                        $uploadedFiles[] = $newFileName;
                    }
                }
            }

            // --- PROSES SIMPAN DB ---
            if (count($uploadedFiles) > 0) {
                $stringFoto = implode(',', $uploadedFiles);
                
                // 1. GENERATE KODE TIKET ACAK
                // Format: TKT-TahunBulanTanggal-4AngkaAcak (Contoh: TKT-20260106-4821)
                $tiket_baru = "TKT-" . date('Ymd') . "-" . rand(1000, 9999);

                // 2. QUERY INSERT DENGAN NOMOR TIKET
                $query = "INSERT INTO pengaduan (nomor_tiket, nama_pelapor, jenis_aset, lokasi_aset, keterangan, bukti_foto, status) 
                          VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
                
                $stmt = $koneksi->prepare($query);
                // "ssssss" -> 6 string (tiket, nama, jenis, lokasi, ket, foto)
                $stmt->bind_param("ssssss", $tiket_baru, $nama, $jenis, $lokasi, $ket, $stringFoto);
                
                if ($stmt->execute()) {
                    $status = "success";
                    // Pesan khusus menampilkan tiket
                    $pesan = "Laporan Diterima! Simpan Nomor Tiket Anda: <strong>" . $tiket_baru . "</strong>";
                    unset($_SESSION['old_input']); 
                } else {
                    $status = "error";
                    $pesan = "Gagal menyimpan ke Database: " . $stmt->error;
                }
            } else {
                $status = "error";
                $pesan = "Gagal upload foto atau foto kosong.";
            }
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .status-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0,0,0,0.1); text-align: center; max-width: 500px; width: 90%; }
        .icon-box { width: 80px; height: 80px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 40px; }
        .success-icon { background: #d1e7dd; color: #198754; }
        .error-icon { background: #f8d7da; color: #dc3545; }
        .ticket-box { background: #e7f1ff; border: 2px dashed #0d6efd; padding: 15px; border-radius: 10px; margin: 20px 0; }
        .ticket-code { font-size: 1.5rem; font-weight: bold; color: #0d6efd; letter-spacing: 2px; }
    </style>
</head>
<body>

    <div class="status-card">
        <?php if ($status == 'success'): ?>
            <div class="icon-box success-icon"><i class="fas fa-check"></i></div>
            <h3 class="fw-bold text-success">Laporan Berhasil!</h3>
            <p class="text-muted">Terima kasih telah melapor. Petugas kami akan segera menindaklanjuti.</p>
            
            <div class="ticket-box">
                <p class="mb-1 small text-uppercase text-muted fw-bold">Nomor Tiket Anda</p>
                <div class="ticket-code"><?= $tiket_baru; ?></div>
                <p class="mb-0 small text-danger mt-1">*Mohon catat/screenshot kode ini untuk cek status.</p>
            </div>

            <a href="index.php" class="btn btn-primary w-100 rounded-pill fw-bold">Kembali ke Beranda</a>

        <?php else: ?>
            <div class="icon-box error-icon"><i class="fas fa-times"></i></div>
            <h3 class="fw-bold text-danger">Gagal Terkirim!</h3>
            <p class="text-muted"><?= $pesan; ?></p>
            <a href="index.php?page=lapor" class="btn btn-secondary w-100 rounded-pill">Coba Lagi</a>
        <?php endif; ?>
    </div>

</body>
</html>