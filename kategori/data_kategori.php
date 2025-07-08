<?php
// Cek akses admin
if ($_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak. Halaman ini hanya untuk admin.</div>";
    exit;
}

// Ambil data kategori
$kategori = $koneksi->query("SELECT * FROM rachel_kategori ORDER BY nama_kategori ASC");
?>

<h3 class="mb-4 fw-bold text-white">Manajemen Kategori</h3>
<a href="index.php?folder=kategori&page=form_kategori" class="btn btn-orange mb-3">+ Tambah Kategori</a>

<?php if ($kategori->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-orange table-transparent">
            <thead class="table-warning">
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama Kategori</th>
                    <th class="judul-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $kategori->fetch_assoc()): ?>
                    <tr>
                        <td class="col-no"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="index.php?folder=kategori&page=form_kategori&id=<?= $row['id'] ?>"
                                class="btn btn-warning btn-sm">Edit</a>
                            <a onclick="return confirm('Yakin hapus kategori ini?')"
                                href="proses/proses_kategori.php?hapus=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Belum ada kategori.</div>
<?php endif ?>