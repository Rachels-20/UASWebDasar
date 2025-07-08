<?php
// Validasi session admin
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-warning'>ID tidak valid.</div>";
    exit;
}

$id = intval($_GET['id']);
$data = $koneksi->query("SELECT * FROM rachel_tanggapan WHERE pengaduan_id = $id")->fetch_assoc();
$from = $_GET['from'] ?? 'tanggapan'; // default jika tidak dikirim

?>

<h3 class="fw-bold mb-4">Perbarui Status Pengaduan</h3>

<form action="proses/proses_edit_tanggapan.php" method="POST">
    <input type="hidden" name="pengaduan_id" value="<?= $id ?>">

    <div class="mb-3">
        <label class="form-label">Status Baru</label>
        <select name="status" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="Selesai">Selesai</option>
            <option value="Ditolak">Ditolak</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Catatan Admin Saat Selesai</label>
        <textarea name="catatan_admin_selesai" class="form-control" rows="4"
            required><?= htmlspecialchars($data['catatan_admin_selesai'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    <a href="index.php?folder=admin&page=<?= htmlspecialchars($from) ?>" class="btn btn-warning mb-3 mt-3">
        Kembali
    </a>
</form>