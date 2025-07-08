<?php
// Cek apakah user sudah login dan berlevel mahasiswa
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'mahasiswa') {
    echo "<div class='alert alert-danger'>Akses ditolak. Halaman ini hanya untuk mahasiswa.</div>";
    exit;
}

// Ambil email dari session untuk cari user_id
$email = $_SESSION['email'];
$getUser = $koneksi->prepare("SELECT id FROM rachel_users WHERE email = ?");
$getUser->bind_param("s", $email);
$getUser->execute();
$result = $getUser->get_result();

if ($result->num_rows !== 1) {
    echo "<div class='alert alert-danger'>Data pengguna tidak ditemukan.</div>";
    exit;
}

$user_id = $result->fetch_assoc()['id'];

// Ambil kategori
$kategori_result = $koneksi->query("SELECT id, nama_kategori FROM rachel_kategori ORDER BY nama_kategori ASC");
$kategori_list = $kategori_result->fetch_all(MYSQLI_ASSOC);

// Ambil parameter pencarian
$filter_kategori = $_GET['kategori'] ?? '';
$search_judul = $_GET['search'] ?? '';

// Query dasar
$sql = "SELECT p.*, k.nama_kategori FROM rachel_pengaduan p LEFT JOIN rachel_kategori k ON p.kategori_id = k.id WHERE p.user_id = ?";
$params = [$user_id];
$types = "i";

// Tambahkan filter kategori jika ada
if (!empty($filter_kategori)) {
    $sql .= " AND p.kategori_id = ?";
    $params[] = $filter_kategori;
    $types .= "i";
}

// Tambahkan pencarian judul jika ada
if (!empty($search_judul)) {
    $sql .= " AND p.judul LIKE ?";
    $params[] = "%$search_judul%";
    $types .= "s";
}

$sql .= " ORDER BY p.created_at DESC";
$query = $koneksi->prepare($sql);
$query->bind_param($types, ...$params);
$query->execute();
$pengaduan = $query->get_result();
?>

<h3 class="fw-bold mb-4 text-white">Status Pengaduan Saya</h3>

<!-- Filter dan Pencarian -->
<form method="get" class="row mb-4 justify-content-end g-2 align-items-end">
    <input type="hidden" name="folder" value="mahasiswa">
    <input type="hidden" name="page" value="status_laporan">

    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Cari judul..."
            value="<?= htmlspecialchars($search_judul) ?>">
    </div>
    <div class="col-auto">
        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori_list as $kat): ?>
                <option value="<?= $kat['id'] ?>" <?= ($filter_kategori == $kat['id'] ? 'selected' : '') ?>>
                    <?= $kat['nama_kategori'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-warning text-white">Terapkan</button>
        <a href="index.php?folder=mahasiswa&page=status_laporan" class="btn btn-secondary">Reset</a>
    </div>
</form>

<?php if ($pengaduan->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-transparent table-orange">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="judul-text-center">Judul</th>
                    <th class="judul-text-center">Kategori</th>
                    <th class="judul-text-center">Status</th>
                    <th class="judul-text-center">Waktu</th>
                    <th class="judul-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $pengaduan->fetch_assoc()): ?>
                    <tr>
                        <td class="col-no text-center"><?= $no++ ?></td>
                        <td class="judul-text-center"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="judul-text-center"><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                        <td class="judul-text-center">
                            <?php
                            $badge = match ($row['status']) {
                                'Menunggu' => 'secondary',
                                'Diproses' => 'warning',
                                'Selesai' => 'success',
                                'Ditolak' => 'danger',
                                default => 'light'
                            };
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= $row['status'] ?></span>
                        </td>
                        <td class="judul-text-center"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="judul-text-center">
                            <a href="index.php?folder=mahasiswa&page=detail_laporan&id=<?= $row['id'] ?>"
                                class="btn btn-sm text-white" style="background-color: #ff7f00;">Detail</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Anda belum mengirimkan Pengaduan apa pun.</div>
<?php endif ?>