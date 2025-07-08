<?php
// Cek level admin
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

require_once(__DIR__ . '/../koneksi.php');

// Ambil semua data pengguna
$query = "SELECT * FROM rachel_users ORDER BY nama ASC";
$result = $koneksi->query($query);
?>

<h3 class="mb-4 fw-bold text-white">Daftar Pengguna</h3>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-transparent table-orange">
            <thead class="table-primary">
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th class="judul-text-center">Level</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="col-no"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td class="judul-text-center"><?= htmlspecialchars($row['level']) ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Belum ada data pengguna.</div>
<?php endif; ?>