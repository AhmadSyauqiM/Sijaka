<?php
session_start();
require '../koneksi.php';

// --- 1. CEK KEAMANAN ---
// Pastikan yang login adalah pekerja
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'pekerja') {
    header("Location: ../admin/login.php");
    exit;
}

// --- 2. AMBIL DATA PEKERJA ---
$pekerja_id = $_SESSION['id_user']; // ID user dari session login
$nama_pekerja = $_SESSION['nama'];

// --- 3. FUNGSI UTAMA (DISESUAIKAN DB) ---

// A. Ambil tugas aktif (Proses & Menunggu Validasi)
function getTugasAktif($koneksi, $pekerja_id) {
    // Query langsung ke tabel pengaduan tanpa JOIN tabel kategori/bidang yang tidak ada
    $query = "SELECT * FROM pengaduan 
              WHERE id_pekerja = '$pekerja_id' 
              AND status IN ('Proses', 'Menunggu Validasi')
              ORDER BY tanggal_lapor DESC";
    return mysqli_query($koneksi, $query);
}

// B. Ambil riwayat selesai bulan ini
function getRiwayatSelesai($koneksi, $pekerja_id) {
    $bulan_ini = date('Y-m');
    // Asumsi: tgl_selesai dicatat di kolom updated_at atau tgl_selesai jika ada
    // Kita gunakan tanggal_lapor sebagai sorting saja jika tgl_selesai belum ada
    $query = "SELECT * FROM pengaduan 
              WHERE id_pekerja = '$pekerja_id' 
              AND status = 'Selesai'
              ORDER BY tanggal_lapor DESC";
    return mysqli_query($koneksi, $query);
}

// C. Hitung statistik
function hitungStatistikPekerja($koneksi, $pekerja_id) {
    $stats = [
        'total_tugas' => 0,
        'proses' => 0,
        'validasi' => 0,
        'selesai' => 0
    ];
    
    // Total Tugas (Semua yang pernah ditugaskan)
    $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengaduan WHERE id_pekerja = '$pekerja_id'");
    $stats['total_tugas'] = mysqli_fetch_assoc($q)['total'];
    
    // Sedang Proses
    $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengaduan WHERE id_pekerja = '$pekerja_id' AND status = 'Proses'");
    $stats['proses'] = mysqli_fetch_assoc($q)['total'];
    
    // Menunggu Validasi
    $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengaduan WHERE id_pekerja = '$pekerja_id' AND status = 'Menunggu Validasi'");
    $stats['validasi'] = mysqli_fetch_assoc($q)['total'];
    
    // Selesai Total
    $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengaduan WHERE id_pekerja = '$pekerja_id' AND status = 'Selesai'");
    $stats['selesai'] = mysqli_fetch_assoc($q)['total'];
    
    return $stats;
}

// --- 4. PROSES FORM (ACTION) ---

// A. UPDATE PROGRESS & UPLOAD FOTO
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update_progress') {
    $laporan_id = mysqli_real_escape_string($koneksi, $_POST['laporan_id']);
    $catatan_pekerja = mysqli_real_escape_string($koneksi, $_POST['catatan_pekerja']);
    
    // Upload Foto
    $foto_perbaikan = null;
    if (isset($_FILES['foto_sesudah']) && $_FILES['foto_sesudah']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto_sesudah']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = 'selesai_' . time() . '_' . $laporan_id . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['foto_sesudah']['tmp_name'], $upload_path)) {
                $foto_perbaikan = $new_filename;
            }
        }
    }
    
    // Update Database
    // Kita ubah status jadi 'Menunggu Validasi'
    $query = "UPDATE pengaduan SET 
              status = 'Menunggu Validasi',
              catatan_petugas = '$catatan_pekerja'"; // Kolom di DB kamu: catatan_petugas
              
    if ($foto_perbaikan) {
        $query .= ", foto_perbaikan = '$foto_perbaikan'"; // Kolom di DB kamu: foto_perbaikan
    }
    
    // Tambahkan tgl_selesai (waktu submit)
    $query .= ", tgl_selesai = NOW()";
    
    $query .= " WHERE id = '$laporan_id' AND id_pekerja = '$pekerja_id'";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Laporan terkirim! Menunggu validasi Kabid.';
    } else {
        $_SESSION['pesan_error'] = 'Gagal: ' . mysqli_error($koneksi);
    }
    
    header("Location: index.php");
    exit;
}

// B. LAPOR KENDALA (Fitur Tambahan - Opsional)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'laporkan_kendala') {
    // Karena belum ada tabel khusus kendala, kita simpan di catatan_petugas dulu
    // tapi tidak mengubah status jadi selesai.
    $laporan_id = mysqli_real_escape_string($koneksi, $_POST['laporan_id']);
    $kendala = mysqli_real_escape_string($koneksi, $_POST['kendala']);
    
    $catatan_darurat = "[DARURAT] " . $kendala;
    
    $query = "UPDATE pengaduan SET 
              catatan_petugas = '$catatan_darurat' 
              WHERE id = '$laporan_id' AND id_pekerja = '$pekerja_id'";
              
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Kendala dilaporkan. Hubungi Kabid segera.';
    } else {
        $_SESSION['pesan_error'] = 'Error: ' . mysqli_error($koneksi);
    }
    header("Location: index.php");
    exit;
}

// --- 5. EKSEKUSI DATA ---
$tugas_aktif = getTugasAktif($koneksi, $pekerja_id);
$riwayat = getRiwayatSelesai($koneksi, $pekerja_id);
$stats = hitungStatistikPekerja($koneksi, $pekerja_id);

// --- 6. NOTIFIKASI & TAB ---
$pesan = $_SESSION['pesan'] ?? '';
$pesan_error = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan'], $_SESSION['pesan_error']);

$tab_aktif = isset($_GET['tab']) ? $_GET['tab'] : 'tugas';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pekerja - <?= $nama_pekerja; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f0f8ff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-pekerja { background-color: #e3f2fd; }
        .card-task { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        .card-task-header { background-color: #bbdefb; border-radius: 15px 15px 0 0; padding: 15px; }
        .btn-pekerja { background-color: #2196f3; color: white; border-radius: 25px; padding: 10px 20px; }
        .btn-pekerja:hover { background-color: #0d8bf2; color: white; }
        
        /* Badge Status */
        .badge-status { font-size: 0.8rem; padding: 5px 12px; border-radius: 20px; }
        .badge-proses { background-color: #ff9800; color: white; } /* Oranye */
        .badge-validasi { background-color: #4caf50; color: white; } /* Hijau */
        .badge-normal { background-color: #757575; color: white; }

        .stat-card { background: linear-gradient(135deg, #bbdefb 0%, #e3f2fd 100%); border-radius: 10px; padding: 15px; }
        .stat-icon { font-size: 2rem; color: #2196f3; }
        
        @media (max-width: 768px) {
            .card-task { margin-bottom: 15px; }
            .btn-pekerja { width: 100%; margin-bottom: 10px; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-pekerja shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-hard-hat me-2"></i>PEKERJA LAPANGAN
            </a>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-md-block">
                    <span class="fw-bold"><?= $nama_pekerja; ?></span><br>
                    <small class="text-muted">Divisi <?= $_SESSION['divisi']; ?></small>
                </div>
                <a href="../admin/logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if($pesan): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $pesan; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if($pesan_error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $pesan_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Tugas</h6>
                            <h3 class="mb-0"><?= $stats['total_tugas']; ?></h3>
                        </div>
                        <i class="fas fa-tasks stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Dalam Proses</h6>
                            <h3 class="mb-0"><?= $stats['proses']; ?></h3>
                        </div>
                        <i class="fas fa-tools stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Validasi</h6>
                            <h3 class="mb-0"><?= $stats['validasi']; ?></h3>
                        </div>
                        <i class="fas fa-check-double stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Selesai</h6>
                            <h3 class="mb-0"><?= $stats['selesai']; ?></h3>
                        </div>
                        <i class="fas fa-flag-checkered stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= ($tab_aktif == 'tugas') ? 'active' : ''; ?>" 
                        id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas" type="button">
                    <i class="fas fa-list-check me-1"></i> Tugas Saya
                    <?php if(mysqli_num_rows($tugas_aktif) > 0): ?>
                    <span class="badge bg-primary ms-1"><?= mysqli_num_rows($tugas_aktif); ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= ($tab_aktif == 'riwayat') ? 'active' : ''; ?>" 
                        id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button">
                    <i class="fas fa-history me-1"></i> Riwayat Selesai
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade <?= ($tab_aktif == 'tugas') ? 'show active' : ''; ?>" id="tugas" role="tabpanel">
                
                <?php if(mysqli_num_rows($tugas_aktif) > 0): ?>
                    <?php while($tugas = mysqli_fetch_assoc($tugas_aktif)): ?>
                    <div class="card card-task">
                        <div class="card-task-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        <?= $tugas['jenis_aset']; ?> di <?= htmlspecialchars(substr($tugas['lokasi_aset'], 0, 20)); ?>...
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-secondary"><?= $tugas['jenis_aset']; ?></span>
                                        <span class="badge <?= ($tugas['status'] == 'Proses') ? 'badge-proses' : 'badge-validasi'; ?>">
                                            <?= $tugas['status']; ?>
                                        </span>
                                    </div>
                                </div>
                                <span class="badge bg-dark">#<?= $tugas['nomor_tiket']; ?></span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="mb-3"><?= htmlspecialchars($tugas['keterangan']); ?></p>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong><i class="fas fa-map-marker-alt me-1"></i> Lokasi Detail:</strong></p>
                                            <p><?= htmlspecialchars($tugas['lokasi_aset']); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong><i class="fas fa-calendar me-1"></i> Tanggal Lapor:</strong></p>
                                            <p><?= date('d/m/Y', strtotime($tugas['tanggal_lapor'])); ?></p>
                                        </div>
                                    </div>

                                    <?php if($tugas['bukti_foto']): 
                                        $fotos = explode(',', $tugas['bukti_foto']);
                                    ?>
                                    <div class="mb-3">
                                        <p class="mb-1"><strong><i class="fas fa-camera me-1"></i> Foto Kerusakan:</strong></p>
                                        <img src="../uploads/<?= $fotos[0]; ?>" class="img-fluid rounded" style="max-height: 150px;">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-grid gap-2">
                                        <?php if($tugas['status'] == 'Proses'): ?>
                                        <button type="button" class="btn btn-pekerja" 
                                                data-bs-toggle="modal" data-bs-target="#modalUpdate<?= $tugas['id']; ?>">
                                            <i class="fas fa-upload me-1"></i> Laporkan Hasil
                                        </button>
                                        <?php endif; ?>
                                        
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($tugas['lokasi_aset']); ?>" 
                                           target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-map-marked-alt me-1"></i> Buka Maps
                                        </a>
                                        
                                        <button type="button" class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#modalDarurat<?= $tugas['id']; ?>">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Kendala
                                        </button>
                                        
                                        <button type="button" class="btn btn-outline-secondary" 
                                                data-bs-toggle="modal" data-bs-target="#modalDetail<?= $tugas['id']; ?>">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalUpdate<?= $tugas['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header" style="background-color: #bbdefb;">
                                        <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Lapor Hasil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="aksi" value="update_progress">
                                        <input type="hidden" name="laporan_id" value="<?= $tugas['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Foto Hasil Perbaikan:</label>
                                            <input type="file" name="foto_sesudah" class="form-control" accept="image/*" required>
                                            <small class="text-muted">Upload foto kondisi setelah diperbaiki.</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Catatan Pengerjaan:</label>
                                            <textarea name="catatan_pekerja" class="form-control" rows="3" required placeholder="Contoh: Lubang sudah ditambal aspal hotmix..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-pekerja">Kirim Laporan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalDarurat<?= $tugas['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="POST">
                                    <div class="modal-header" style="background-color: #ffcdd2;">
                                        <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Lapor Kendala</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="aksi" value="laporkan_kendala">
                                        <input type="hidden" name="laporan_id" value="<?= $tugas['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Deskripsi Kendala:</label>
                                            <textarea name="kendala" class="form-control" rows="3" required placeholder="Jelaskan kenapa pekerjaan terhambat..."></textarea>
                                        </div>
                                        <div class="alert alert-warning small">
                                            Pesan ini akan tercatat dan terbaca oleh Kabid.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Kirim Kendala</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalDetail<?= $tugas['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Laporan #<?= $tugas['nomor_tiket']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Pelapor:</strong> <?= $tugas['nama_pelapor']; ?></p>
                                    <p><strong>Jenis Aset:</strong> <?= $tugas['jenis_aset']; ?></p>
                                    <p><strong>Lokasi:</strong> <?= $tugas['lokasi_aset']; ?></p>
                                    <p><strong>Keterangan:</strong><br><?= nl2br(htmlspecialchars($tugas['keterangan'])); ?></p>
                                    <hr>
                                    <p><strong>Status:</strong> <span class="badge bg-info"><?= $tugas['status']; ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-check fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Tidak ada tugas aktif</h4>
                        <p class="text-muted">Santai dulu Mang, belum ada perintah dari Kabid.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade <?= ($tab_aktif == 'riwayat') ? 'show active' : ''; ?>" id="riwayat" role="tabpanel">
                <?php if(mysqli_num_rows($riwayat) > 0): ?>
                    <div class="row">
                        <?php while($histori = mysqli_fetch_assoc($riwayat)): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card card-task">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold"><?= $histori['jenis_aset']; ?> - <?= $histori['lokasi_aset']; ?></h6>
                                        <span class="badge bg-success">Selesai</span>
                                    </div>
                                    <p class="small text-muted mb-2">Tiket: #<?= $histori['nomor_tiket']; ?></p>
                                    
                                    <?php if($histori['foto_perbaikan']): ?>
                                        <img src="../uploads/<?= $histori['foto_perbaikan']; ?>" class="img-fluid rounded mb-2" style="max-height: 120px;">
                                    <?php endif; ?>
                                    
                                    <div class="bg-light p-2 rounded small">
                                        <strong>Catatan Anda:</strong><br>
                                        <?= $histori['catatan_petugas']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada riwayat</h4>
                        <p>Kerjaan yang sudah beres bakal muncul di sini.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh
        if (window.location.hash !== '#riwayat') {
            setTimeout(function() { location.reload(); }, 60000);
        }
    </script>
</body>
</html>