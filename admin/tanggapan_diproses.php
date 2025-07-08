<?php
// Cek level akses
if ($_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak. Halaman ini hanya untuk admin.</div>";
    exit;
}

$keyword = $_GET['cari'] ?? '';
$kategoriFilter = $_GET['kategori'] ?? '';

// Ambil semua kategori
$kategoriList = $koneksi->query("SELECT id, nama_kategori FROM rachel_kategori ORDER BY nama_kategori ASC");

// Query data pengaduan diproses
$query = "
    SELECT p.id, p.judul, p.status, p.created_at, t.tanggal AS tanggal_tanggapan,
           u.nama AS nama_pengguna, k.nama_kategori
    FROM rachel_pengaduan p
    JOIN rachel_users u ON p.user_id = u.id
    JOIN rachel_tanggapan t ON t.pengaduan_id = p.id
    JOIN rachel_kategori k ON p.kategori_id = k.id
    WHERE p.status = 'Diproses'
";

if (!empty($kategoriFilter)) {
    $kategoriEscaped = $koneksi->real_escape_string($kategoriFilter);
    $query .= " AND k.id = '$kategoriEscaped'";
}
if (!empty($keyword)) {
    $keywordEscaped = $koneksi->real_escape_string($keyword);
    $query .= " AND p.judul LIKE '%$keywordEscaped%'";
}
$query .= " ORDER BY t.tanggal DESC";

$result = $koneksi->query($query);
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

<h3 class="mb-3 fw-bold text-white">Daftar Pengaduan Berstatus Diproses</h3>

<!-- FORM PENCARIAN & KATEGORI (RATA KANAN) -->
<form method="get" class="row mb-4 justify-content-end g-2 align-items-end">
    <input type="hidden" name="folder" value="admin">
    <input type="hidden" name="page" value="tanggapan_diproses">

    <div class="col-auto">
        <input type="text" name="cari" class="form-control" placeholder="Judul..."
            value="<?= htmlspecialchars($keyword) ?>">
    </div>

    <div class="col-auto">
        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php while ($k = $kategoriList->fetch_assoc()): ?>
                <option value="<?= $k['id'] ?>" <?= $kategoriFilter == $k['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['nama_kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-auto d-flex gap-2">
        <button type="submit" class="btn btn-warning text-white mt-auto">Terapkan</button>
        <a href="index.php?folder=admin&page=tanggapan_diproses" class="btn btn-secondary mt-auto">Reset</a>
    </div>
</form>

<!-- TABEL DATA -->
<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-transparent table-orange">
            <thead class="table-info">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Pengguna</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Judul</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal Pengaduan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark"><?= $row['status'] ?></span>
                        </td>
                        <td class="text-center"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="index.php?folder=admin&page=detail_tanggapan&id=<?= $row['id'] ?>&from=tanggapan_diproses"
                                    class="btn btn-sm btn-orange">Detail</a>
                                <a href="index.php?folder=admin&page=form_edit_tanggapan&id=<?= $row['id'] ?>&from=tanggapan_diproses"
                                    class="btn btn-sm btn-warning">Edit</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        Tidak ada pengaduan dengan status diproses
        <?= ($keyword || $kategoriFilter) ? " sesuai dengan filter." : "." ?>
    </div>
<?php endif; ?>