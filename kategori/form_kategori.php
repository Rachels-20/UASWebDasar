<?php
// Cek akses admin
if ($_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

$id = $_GET['id'] ?? '';
$edit = false;

if ($id) {
    $result = $koneksi->query("SELECT * FROM rachel_kategori WHERE id = '$id'");
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $edit = true;
    }
}
?>

<h3 class="mb-4 fw-bold"><?= $edit ? 'Edit' : 'Tambah' ?> Kategori</h3>

<form action="proses/proses_kategori.php" method="POST">
    <?php if ($edit): ?>
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" class="form-control" name="nama_kategori" id="nama_kategori"
            value="<?= $edit ? htmlspecialchars($data['nama_kategori']) : '' ?>" required>
    </div>
    <button type="submit" name="<?= $edit ? 'simpan' : 'simpan' ?>"
        class="btn btn-primary"><?= $edit ? 'Simpan' : 'Simpan' ?></button>
    <a href="index.php?folder=kategori&page=data_kategori" class="btn btn-warning">Kembali</a>
</form>