<?php
session_start();
require '../koneksi.php';

// --- 1. CEK KEAMANAN ---
// Pastikan user adalah 'bidang' (Kabid)
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'bidang') {
    header("Location: ../admin/login.php");
    exit;
}

// --- 2. AMBIL DATA DIVISI KABID YANG LOGIN ---
$nama_bidang = $_SESSION['divisi']; // Contoh: 'Bina Marga' atau 'Cipta Karya'

// LOGIKA PEMBAGIAN ASET BERDASARKAN DIVISI (Manual Mapping)
$filter_aset = "";
if ($nama_bidang == 'Bina Marga') {
    // Bina Marga urus Jalan & Jembatan
    $filter_aset = "'Jalan', 'Jembatan'"; 
} else {
    // Cipta Karya urus Gedung, Drainase, Lainnya
    $filter_aset = "'Gedung', 'Drainase', 'Lainnya'";
}

// --- 3. FUNGSI UTAMA (SINKRON DB SIJAKA) ---

// A. Ambil laporan sesuai divisi
function getLaporanBidang($koneksi, $filter_aset) {
    // Query ini sudah disesuaikan dengan nama kolom asli di DB
    $query = "SELECT p.*, 
                     p.bukti_foto as foto_sebelum, 
                     p.foto_perbaikan as foto_sesudah,
                     p.catatan_petugas as catatan_pekerja
              FROM pengaduan p
              WHERE p.jenis_aset IN ($filter_aset)
              ORDER BY FIELD(p.status, 'Pending', 'Proses', 'Menunggu Validasi', 'Selesai', 'Ditolak'), 
                       p.tanggal_lapor DESC";
    return mysqli_query($koneksi, $query);
}

// B. Ambil daftar pekerja di divisi ini
function getPekerjaBidang($koneksi, $nama_bidang) {
    // Ambil user yang role='pekerja' dan divisinya sama
    $query = "SELECT id, nama_lengkap 
              FROM admin 
              WHERE role = 'pekerja' AND divisi = '$nama_bidang'
              ORDER BY nama_lengkap";
    return mysqli_query($koneksi, $query);
}

// C. Hitung statistik per status
function hitungStatusBidang($koneksi, $filter_aset, $status) {
    $q = mysqli_query($koneksi, 
        "SELECT COUNT(*) as jlh 
         FROM pengaduan 
         WHERE jenis_aset IN ($filter_aset) AND status = '$status'");
    $data = mysqli_fetch_assoc($q);
    return $data['jlh'];
}

// --- 4. PROSES FORM (ACTION) ---

// A. DISPOSISI TUGAS (TUGASKAN)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tugaskan') {
    $laporan_id = mysqli_real_escape_string($koneksi, $_POST['laporan_id']);
    $pekerja_id = mysqli_real_escape_string($koneksi, $_POST['pekerja_id']);
    // Tanggal deadline tidak disimpan ke DB karena tidak ada kolomnya, 
    // tapi bisa ditambahkan fitur kirim WA nanti.
    
    // Update status jadi Proses & isi id_pekerja
    $query = "UPDATE pengaduan SET 
              id_pekerja = '$pekerja_id',
              status = 'Proses'
              WHERE id = '$laporan_id'";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Tugas berhasil diberikan ke pekerja';
    } else {
        $_SESSION['pesan_error'] = 'Gagal: ' . mysqli_error($koneksi);
    }
    header("Location: index.php");
    exit;
}

// B. VALIDASI PENYELESAIAN (QC)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'validasi') {
    $laporan_id = mysqli_real_escape_string($koneksi, $_POST['laporan_id']);
    $tindakan = mysqli_real_escape_string($koneksi, $_POST['tindakan']);
    // $catatan_qc = $_POST['catatan_qc']; // Opsi: Simpan ini jika kolom ada
    
    // Logika Status
    // Setujui -> Selesai
    // Tolak -> Proses (Kembali ke pekerja untuk diperbaiki)
    $status_baru = ($tindakan == 'setujui') ? 'Selesai' : 'Proses';
    
    // Kita update tgl_selesai jika disetujui
    $sql_tambahan = "";
    if($status_baru == 'Selesai') {
        $sql_tambahan = ", tgl_selesai = NOW()";
    }

    $query = "UPDATE pengaduan SET 
              status = '$status_baru' 
              $sql_tambahan
              WHERE id = '$laporan_id'";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = "Laporan telah di-$tindakan";
    } else {
        $_SESSION['pesan_error'] = 'Gagal Validasi: ' . mysqli_error($koneksi);
    }
    header("Location: index.php");
    exit;
}

// C. KEMBALIKAN KE ADMIN (Salah Kamar)
if (isset($_GET['kembalikan'])) {
    $laporan_id = mysqli_real_escape_string($koneksi, $_GET['kembalikan']);
    
    // Reset status jadi Pending dan hapus pekerja
    $query = "UPDATE pengaduan SET 
              status = 'Pending',
              id_pekerja = NULL
              WHERE id = '$laporan_id'";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Laporan berhasil dikembalikan ke Admin (Pending)';
    }
    header("Location: index.php");
    exit;
}

// --- 5. EKSEKUSI DATA UTAMA ---
$laporan = getLaporanBidang($koneksi, $filter_aset);
$pekerja = getPekerjaBidang($koneksi, $nama_bidang);

// Hitung statistik
$total_pending  = hitungStatusBidang($koneksi, $filter_aset, 'Pending');
$total_proses   = hitungStatusBidang($koneksi, $filter_aset, 'Proses');
$total_validasi = hitungStatusBidang($koneksi, $filter_aset, 'Menunggu Validasi');
$total_selesai  = hitungStatusBidang($koneksi, $filter_aset, 'Selesai');

// Pesan Notifikasi
$pesan = $_SESSION['pesan'] ?? '';
$pesan_error = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan'], $_SESSION['pesan_error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kabid - <?= $nama_bidang; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-brand { background-color: #e3f2fd; color: #0d6efd !important; }
        .btn-light-blue { background-color: #e3f2fd; border-color: #bbdefb; color: #0d6efd; }
        .btn-light-blue:hover { background-color: #bbdefb; color: #0a58ca; }
        .card-header-light { background-color: #ffffff; border-bottom: 1px solid #f0f0f0; }
        .badge-status { font-size: 0.75rem; padding: 6px 12px; border-radius: 30px; font-weight: 500; }
        
        /* Warna Status Modern */
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-proses { background-color: #cce5ff; color: #004085; border: 1px solid #b8daff; }
        .status-validasi { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .status-selesai { background-color: #198754; color: white; }
        .status-ditolak { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .modal-content { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold px-3 py-2 rounded" href="#">
                <i class="fas fa-user-tie me-2"></i>KABID <?= strtoupper($nama_bidang); ?>
            </a>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-md-block">
                    <span class="text-muted small">Selamat Datang,</span><br>
                    <span class="fw-bold text-dark"><?= $_SESSION['nama']; ?></span>
                </div>
                <a href="../admin/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if($pesan): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $pesan; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if($pesan_error): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $pesan_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Pending</h6>
                            <h3 class="mb-0 fw-bold"><?= $total_pending; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-tools fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Diproses</h6>
                            <h3 class="mb-0 fw-bold"><?= $total_proses; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-check-double fa-2x text-info"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Validasi</h6>
                            <h3 class="mb-0 fw-bold"><?= $total_validasi; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-flag-checkered fa-2x text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Selesai</h6>
                            <h3 class="mb-0 fw-bold"><?= $total_selesai; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header card-header-light py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-inbox me-2 text-primary"></i>Kotak Masuk (<?= $nama_bidang; ?>)
                    </h5>
                    <div class="text-muted small">
                        <i class="fas fa-filter me-1"></i>Filter: <?= str_replace("'", "", $filter_aset); ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th>Tiket</th>
                                <th>Jenis & Lokasi</th>
                                <th>Tanggal</th>
                                <th width="120">Status</th>
                                <th>Pekerja</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($laporan)): 
                                $st = $row['status'];
                                $status_class = 'status-pending';
                                if($st == 'Proses') $status_class = 'status-proses';
                                if($st == 'Menunggu Validasi') $status_class = 'status-validasi';
                                if($st == 'Selesai') $status_class = 'status-selesai';
                                if($st == 'Ditolak') $status_class = 'status-ditolak';
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><span class="badge bg-secondary rounded-pill"><?= $row['nomor_tiket']; ?></span></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['jenis_aset']; ?></div>
                                    <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= $row['lokasi_aset']; ?></small>
                                </td>
                                <td class="text-muted small">
                                    <?= date('d/m/Y', strtotime($row['tanggal_lapor'])); ?><br>
                                    <?= date('H:i', strtotime($row['tanggal_lapor'])); ?>
                                </td>
                                <td>
                                    <span class="badge badge-status <?= $status_class; ?>">
                                        <?= $st; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['id_pekerja']): 
                                        $q_nama = mysqli_query($koneksi, "SELECT nama_lengkap FROM admin WHERE id='{$row['id_pekerja']}'");
                                        $d_nama = mysqli_fetch_assoc($q_nama);
                                    ?>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2" style="width: 25px; height: 25px;">
                                                <i class="fas fa-user small text-secondary"></i>
                                            </div>
                                            <small class="fw-bold text-dark"><?= $d_nama['nama_lengkap']; ?></small>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border">Belum Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-light-blue" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id']; ?>" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if($st == 'Pending'): ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTugaskan<?= $row['id']; ?>" title="Tugaskan">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                        <a href="?kembalikan=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin kembalikan ke Admin?')" title="Tolak/Kembalikan">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                        <?php endif; ?>

                                        <?php if($st == 'Menunggu Validasi'): ?>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalValidasi<?= $row['id']; ?>" title="Validasi">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalDetail<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-info-circle me-2"></i>Detail Laporan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Tiket:</strong> #<?= $row['nomor_tiket']; ?></p>
                                                    <p><strong>Pelapor:</strong> <?= $row['nama_pelapor']; ?></p>
                                                    <p><strong>Lokasi:</strong> <?= $row['lokasi_aset']; ?></p>
                                                    <p><strong>Keterangan:</strong><br><?= nl2br(htmlspecialchars($row['keterangan'])); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-2"><strong>Foto Bukti (Before):</strong></p>
                                                    <?php if($row['foto_sebelum']): 
                                                        $fotos = explode(',', $row['foto_sebelum']); 
                                                        foreach($fotos as $f): ?>
                                                            <img src="../uploads/<?= $f; ?>" class="img-thumbnail mb-2" style="max-height: 150px;">
                                                    <?php endforeach; else: ?>
                                                        <span class="text-muted">Tidak ada foto</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row bg-light p-3 rounded">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Hasil Perbaikan (After):</strong></p>
                                                    <?php if($row['foto_sesudah']): ?>
                                                        <img src="../uploads/<?= $row['foto_sesudah']; ?>" class="img-thumbnail border-success" style="max-height: 150px;">
                                                    <?php else: ?>
                                                        <span class="text-muted small">Belum ada foto perbaikan.</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Catatan Pekerja:</strong></p>
                                                    <p class="small text-dark"><?= $row['catatan_pekerja'] ? $row['catatan_pekerja'] : '-'; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalTugaskan<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Disposisi Tugas</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="aksi" value="tugaskan">
                                                <input type="hidden" name="laporan_id" value="<?= $row['id']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Pilih Pekerja:</label>
                                                    <select name="pekerja_id" class="form-select" required>
                                                        <option value="">-- Pilih Tim <?= $nama_bidang; ?> --</option>
                                                        <?php 
                                                        mysqli_data_seek($pekerja, 0);
                                                        while($p = mysqli_fetch_assoc($pekerja)): 
                                                        ?>
                                                        <option value="<?= $p['id']; ?>"><?= $p['nama_lengkap']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan Deadline (Opsional):</label>
                                                    <input type="date" class="form-control" name="deadline_dummy">
                                                    <small class="text-muted">*Hanya sebagai catatan internal</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Kirim Tugas</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalValidasi<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Validasi Hasil Kerja</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="aksi" value="validasi">
                                                <input type="hidden" name="laporan_id" value="<?= $row['id']; ?>">
                                                
                                                <div class="text-center mb-4">
                                                    <?php if($row['foto_sesudah']): ?>
                                                    <img src="../uploads/<?= $row['foto_sesudah']; ?>" class="img-fluid rounded shadow-sm border border-success" style="max-height: 200px;">
                                                    <p class="text-success mt-2 small fw-bold">Bukti Perbaikan Pekerja</p>
                                                    <?php else: ?>
                                                    <div class="alert alert-warning">Pekerja belum upload foto!</div>
                                                    <?php endif; ?>
                                                </div>

                                                <label class="form-label fw-bold">Keputusan Kabid:</label>
                                                <div class="border rounded p-3">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" name="tindakan" value="setujui" checked id="stj<?= $row['id']; ?>">
                                                        <label class="form-check-label text-success fw-bold" for="stj<?= $row['id']; ?>">
                                                            <i class="fas fa-check me-2"></i>SETUJUI (Selesai)
                                                        </label>
                                                        <div class="small text-muted ms-4">Laporan akan ditutup dan masuk arsip selesai.</div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tindakan" value="tolak" id="tlk<?= $row['id']; ?>">
                                                        <label class="form-check-label text-danger fw-bold" for="tlk<?= $row['id']; ?>">
                                                            <i class="fas fa-times me-2"></i>TOLAK (Revisi)
                                                        </label>
                                                        <div class="small text-muted ms-4">Kembalikan ke pekerja untuk diperbaiki lagi.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success fw-bold">Simpan Keputusan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <?php endwhile; ?>
                            
                            <?php if(mysqli_num_rows($laporan) == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-light-blue"></i>
                                        <p class="mb-0">Tidak ada laporan yang perlu ditangani saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-users-cog me-2 text-info"></i>Tim Pekerja <?= $nama_bidang; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                    mysqli_data_seek($pekerja, 0); 
                    if(mysqli_num_rows($pekerja) > 0):
                        while($p = mysqli_fetch_assoc($pekerja)): 
                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center border rounded p-3 bg-light">
                            <div class="rounded-circle bg-white shadow-sm d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-hard-hat text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?= $p['nama_lengkap']; ?></h6>
                                <small class="text-muted">Status: Aktif</small>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; 
                    else: ?>
                        <div class="col-12 text-center text-muted">
                            Belum ada akun pekerja. Tambahkan di menu Admin.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh halaman setiap 60 detik untuk cek laporan baru
        setTimeout(function() { location.reload(); }, 60000); 
    </script>

</body>
</html>