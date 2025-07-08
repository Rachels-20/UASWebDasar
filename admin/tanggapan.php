<?php
// Cek level akses
if ($_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak. Halaman ini hanya untuk admin.</div>";
    exit;
}

// Ambil keyword dan filter kategori
$keyword = $_GET['keyword'] ?? '';
$kategoriFilter = $_GET['kategori'] ?? '';

// Ambil semua kategori
$kategoriResult = $koneksi->query("SELECT id, nama_kategori FROM rachel_kategori ORDER BY nama_kategori ASC");
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

<h3 class="mb-3 fw-bold text-white">Daftar Pengaduan Belum Ditanggapi</h3>

<!-- FORM PENCARIAN & FILTER KATEGORI -->
<form class="row mb-4 justify-content-end g-2 align-items-end" method="GET">
    <input type="hidden" name="folder" value="admin">
    <input type="hidden" name="page" value="tanggapan">

    <div class="col-auto">
        <input type="text" class="form-control" name="keyword" placeholder="Judul..."
            value="<?= htmlspecialchars($keyword) ?>">
    </div>

    <div class="col-auto">
        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php while ($kat = $kategoriResult->fetch_assoc()): ?>
                <option value="<?= $kat['id'] ?>" <?= $kategoriFilter == $kat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-auto d-flex gap-2">
        <button type="submit" class="btn btn-warning text-white mt-auto">Terapkan</button>
        <a href="index.php?folder=admin&page=tanggapan" class="btn btn-secondary mt-auto">Reset</a>
    </div>
</form>

<?php
// Query pengaduan belum ditanggapi
$query = "
    SELECT p.id, u.nama AS nama_pengguna, p.judul, p.created_at, k.nama_kategori
    FROM rachel_pengaduan p
    JOIN rachel_users u ON p.user_id = u.id
    JOIN rachel_kategori k ON p.kategori_id = k.id
    WHERE p.id NOT IN (SELECT pengaduan_id FROM rachel_tanggapan)
";

if (!empty($keyword)) {
    $safeKeyword = $koneksi->real_escape_string($keyword);
    $query .= " AND p.judul LIKE '%$safeKeyword%'";
}

if (!empty($kategoriFilter)) {
    $safeKategori = $koneksi->real_escape_string($kategoriFilter);
    $query .= " AND k.id = '$safeKategori'";
}

$query .= " ORDER BY p.created_at DESC";
$result = $koneksi->query($query);
?>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-striped table-transparent table-orange">
            <thead class="table-warning">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Pengguna</th>
                    <th class="text-center">Judul</th>
                    <th class="text-center">Kategori</th>
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
                        <td class="text-center"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td class="text-center"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="text-center">
                            <a href="index.php?folder=admin&page=form_tanggapan&id=<?= $row['id'] ?>"
                                class="btn btn-sm btn-orange">Tanggapi</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        Tidak ada pengaduan ditemukan
        <?= !empty($keyword) ? "dengan judul <strong>" . htmlspecialchars($keyword) . "</strong>" : "" ?>
        <?= !empty($kategoriFilter) ? " dan kategori yang dipilih." : "." ?>
    </div>
<?php endif; ?>