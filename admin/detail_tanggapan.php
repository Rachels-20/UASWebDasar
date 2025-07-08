<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Cek admin
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

require_once(__DIR__ . '/../koneksi.php');

// Validasi ID laporan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-warning'>ID laporan tidak valid.</div>";
    exit;
}

$id = intval($_GET['id']);
$from = $_GET['from'] ?? 'tanggapan';

// Ambil laporan + kategori + user
$stmt = $koneksi->prepare("
    SELECT p.*, k.nama_kategori, u.nama AS nama_pengguna, u.email
    FROM rachel_pengaduan p
    JOIN rachel_kategori k ON p.kategori_id = k.id
    JOIN rachel_users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<div class='alert alert-danger'>Laporan tidak ditemukan.</div>";
    exit;
}
$laporan = $result->fetch_assoc();

// Ambil tanggapan (jika ada)
$tanggapan = null;
$stmt2 = $koneksi->prepare("SELECT * FROM rachel_tanggapan WHERE pengaduan_id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$tanggapan_result = $stmt2->get_result();
if ($tanggapan_result->num_rows > 0) {
    $tanggapan = $tanggapan_result->fetch_assoc();
}

// Ambil laporan lain yang masih Diproses DAN belum ditanggapi
$stmt3 = $koneksi->prepare("
    SELECT p.id, p.judul, u.nama AS nama_pengguna, p.created_at
    FROM rachel_pengaduan p
    JOIN rachel_users u ON p.user_id = u.id
    LEFT JOIN rachel_tanggapan t ON p.id = t.pengaduan_id
    WHERE p.status = 'Diproses' AND t.id IS NULL AND p.id != ?
    ORDER BY p.created_at DESC
");
$stmt3->bind_param("i", $id);
$stmt3->execute();
$diproses_result = $stmt3->get_result();
?>

<h3 class="fw-bold mb-4 text-white">Detail Pengaduan Pengguna</h3>

<table class="table table-bordered table-transparent">
    <tr>
        <th>Nama Pengguna</th>
        <td><?= htmlspecialchars($laporan['nama_pengguna']) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($laporan['email']) ?></td>
    </tr>
    <tr>
        <th>Judul</th>
        <td><?= htmlspecialchars($laporan['judul']) ?></td>
    </tr>
    <tr>
        <th>Kategori</th>
        <td><?= htmlspecialchars($laporan['nama_kategori']) ?></td>
    </tr>
    <tr>
        <th>Isi Laporan</th>
        <td><?= nl2br(htmlspecialchars($laporan['isi'])) ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            <?php
            $badge = match ($laporan['status']) {
                'Menunggu' => 'secondary',
                'Diproses' => 'warning',
                'Selesai' => 'success',
                'Ditolak' => 'danger',
                default => 'light'
            };
            ?>
            <span class="badge bg-<?= $badge ?>"><?= $laporan['status'] ?></span>
        </td>
    </tr>
    <tr>
        <th>Waktu Laporan</th>
        <td><?= date('d-m-Y H:i', strtotime($laporan['created_at'])) ?></td>
    </tr>
    <tr>
        <th>Lampiran</th>
        <td>
            <?php if (!empty($laporan['lampiran'])): ?>
                <a href="uploads/<?= htmlspecialchars($laporan['lampiran']) ?>" target="_blank">Lihat Lampiran</a>
            <?php else: ?>
                <span class="text-white">Tidak ada lampiran</span>
            <?php endif; ?>
        </td>
    </tr>
</table>

<!-- TANGGAPAN -->
<?php if ($tanggapan): ?>

    <!-- Catatan admin saat Diproses tetap ditampilkan -->
    <?php if (!empty($tanggapan['catatan_admin_proses'])): ?>
        <div class="card mt-4">
            <div class="card-header bg-warning text-white fw-bold">
                Catatan Admin saat pengaduan Diproses
            </div>
            <div class="card-body">
                <?= nl2br(htmlspecialchars($tanggapan['catatan_admin_proses'])) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Catatan akhir admin hanya jika status Selesai atau Ditolak -->
    <?php if (in_array($laporan['status'], ['Selesai', 'Ditolak']) && !empty($tanggapan['catatan_admin_selesai'])): ?>
        <?php
        $warnaHeader = $laporan['status'] === 'Selesai' ? 'bg-success' : 'bg-danger';
        ?>
        <div class="card mt-4">
            <div class="card-header <?= $warnaHeader ?> text-white fw-bold">
                <?= $laporan['status'] ?></div>
            <div class="card-body">
                <?= nl2br(htmlspecialchars($tanggapan['catatan_admin_selesai'])) ?>
                <div class="mt-2">
                    <small class="text-muted">Ditanggapi pada:
                        <?= date('d-m-Y H:i', strtotime($tanggapan['tanggal'])) ?></small>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-info mt-4">Belum ada tanggapan untuk laporan ini.</div>
<?php endif; ?>

<a href="index.php?folder=admin&page=<?= htmlspecialchars($from) ?>" class="btn btn-warning mb-3 mt-3">
    Kembali
</a>