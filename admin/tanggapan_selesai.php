<?php
// Cek level akses
if ($_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak. Halaman ini hanya untuk admin.</div>";
    exit;
}

// Ambil keyword dan kategori dari URL
$keyword = $_GET['keyword'] ?? '';
$kategori_filter = $_GET['kategori'] ?? '';

// Ambil semua kategori untuk dropdown
$kategori_result = $koneksi->query("SELECT id, nama_kategori FROM rachel_kategori ORDER BY nama_kategori ASC");
?>

<!-- TAB PILLS -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link <?= $_GET['page'] == 'tanggapan' ? 'active' : 'text-white' ?>"
            href="index.php?folder=admin&page=tanggapan">Belum Ditanggapi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $_GET['page'] == 'tanggapan_diproses' ? 'active' : 'text-white' ?>"
            href="index.php?folder=admin&page=tanggapan_diproses">Diproses</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $_GET['page'] == 'tanggapan_selesai' ? 'active' : 'text-white' ?>"
            href="index.php?folder=admin&page=tanggapan_selesai">Selesai / Ditolak</a>
    </li>
</ul>

<h3 class="mb-3 fw-bold text-white">Daftar Pengaduan Sudah Ditanggapi</h3>

<!-- FORM PENCARIAN & FILTER -->
<form class="row mb-4 justify-content-end g-2 align-items-end" method="GET">
    <input type="hidden" name="folder" value="admin">
    <input type="hidden" name="page" value="tanggapan_selesai">

    <div class="col-auto">
        <input type="text" name="keyword" class="form-control" placeholder="Judul..."
            value="<?= htmlspecialchars($keyword) ?>">
    </div>

    <div class="col-auto">
        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php while ($kat = $kategori_result->fetch_assoc()): ?>
                <option value="<?= $kat['id'] ?>" <?= $kategori_filter == $kat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-auto d-flex gap-2">
        <button type="submit" class="btn btn-warning text-white mt-auto">Terapkan</button>
        <a href="index.php?folder=admin&page=tanggapan_selesai" class="btn btn-secondary mt-auto">Reset</a>
    </div>
</form>

<?php
// Query data tanggapan selesai/ditolak + kategori
$query = "
SELECT p.id, p.status, p.judul, p.created_at, u.nama AS nama_pengguna, t.tanggal AS tanggal_tanggapan,
       k.nama_kategori
FROM rachel_pengaduan p
JOIN rachel_users u ON p.user_id = u.id
JOIN rachel_tanggapan t ON t.pengaduan_id = p.id
JOIN rachel_kategori k ON p.kategori_id = k.id
WHERE p.status IN ('Selesai', 'Ditolak')
";

if (!empty($keyword)) {
    $safeKeyword = $koneksi->real_escape_string($keyword);
    $query .= " AND p.judul LIKE '%$safeKeyword%'";
}

if (!empty($kategori_filter) && is_numeric($kategori_filter)) {
    $query .= " AND k.id = " . intval($kategori_filter);
}

$query .= " ORDER BY t.tanggal DESC";
$data = $koneksi->query($query);
?>

<?php if ($data->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-transparent table-orange">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Pengguna</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Judul</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal Pengaduan</th>
                    <th class="text-center">Tanggal Tanggapan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $data->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['judul']) ?></td>
                        <?php
                        $status = $row['status'];
                        $badge = match ($status) {
                            'Menunggu' => 'secondary',
                            'Diproses' => 'warning text-dark',
                            'Ditolak' => 'danger',
                            'Selesai' => 'success',
                            default => 'light'
                        };
                        ?>
                        <td class="text-center"><span class="badge bg-<?= $badge ?>"><?= $status ?></span></td>
                        <td class="text-center"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="text-center"><?= date('d-m-Y H:i', strtotime($row['tanggal_tanggapan'])) ?></td>
                        <td class="text-center">
                            <a href="index.php?folder=admin&page=detail_tanggapan&id=<?= $row['id'] ?>&from=tanggapan_selesai"
                                class="btn btn-sm btn-orange">Detail</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        Tidak ada data pengaduan ditemukan
        <?php if ($keyword): ?> dengan judul <strong><?= htmlspecialchars($keyword) ?></strong><?php endif ?>
        <?php if ($kategori_filter): ?> dan kategori yang dipilih.<?php endif ?>
    </div>
<?php endif; ?>