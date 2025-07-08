<?php
// Cek login & level
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'mahasiswa') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-warning'>ID laporan tidak valid.</div>";
    exit;
}

$id = intval($_GET['id']);

// Ambil data laporan
$query = "
    SELECT p.*, k.nama_kategori AS nama_kategori
    FROM rachel_pengaduan p
    JOIN rachel_kategori k ON p.kategori_id = k.id
    WHERE p.id = ?
";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$hasil = $stmt->get_result();

if ($hasil->num_rows === 0) {
    echo "<div class='alert alert-warning'>Pengaduan tidak ditemukan.</div>";
    exit;
}

$laporan = $hasil->fetch_assoc();

// Ambil data tanggapan
$tanggapan = null;
$tanggapan_query = $koneksi->prepare("SELECT * FROM rachel_tanggapan WHERE pengaduan_id = ?");
$tanggapan_query->bind_param("i", $id);
$tanggapan_query->execute();
$tanggapan_result = $tanggapan_query->get_result();
if ($tanggapan_result->num_rows > 0) {
    $tanggapan = $tanggapan_result->fetch_assoc();
}
?>

<h3 class="fw-bold mb-4 text-white">Detail Pengaduan</h3>

<table class="table table-bordered table-transparent">
    <tr>
        <th>Judul</th>
        <td><?= htmlspecialchars($laporan['judul']) ?></td>
    </tr>
    <tr>
        <th>Kategori</th>
        <td><?= htmlspecialchars($laporan['nama_kategori']) ?></td>
    </tr>
    <tr>
        <th>Isi Pengaduan</th>
        <td><?= nl2br(htmlspecialchars($laporan['isi'])) ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            <?php
            $badgeColor = match ($laporan['status']) {
                'Selesai' => 'success',
                'Diproses' => 'warning',
                'Ditolak' => 'danger',
                default => 'secondary'
            };
            ?>
            <span class="badge text-bg-<?= $badgeColor ?>"><?= $laporan['status'] ?></span>
        </td>
    </tr>

    <tr>
        <th>Waktu Pengaduan</th>
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

<?php if ($tanggapan): ?>

    <!-- Catatan admin saat Diproses -->
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

    <!-- Catatan admin saat pengaduan selesai/ditolak -->
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
    <div class="alert alert-info mt-4">Belum ada tanggapan dari Admin</div>
<?php endif; ?>

<a href="index.php?folder=mahasiswa&page=status_laporan" class="btn btn-warning mt-4">Kembali</a>