<?php
// Cek akses mahasiswa
if ($_SESSION['level'] !== 'mahasiswa') {
    echo "<div class='alert alert-danger'>Akses ditolak. Halaman ini hanya untuk mahasiswa.</div>";
    exit;
}

// Ambil kategori dari database
$kategori = $koneksi->query("SELECT * FROM rachel_kategori ORDER BY nama_kategori ASC");
?>

<h3 class="mb-4 fw-bold text-white">Buat Pengaduan / Aspirasi</h3>

<form action="proses/proses_laporan.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="kategori" class="form-label text-white fw-bold">Kategori</label>
        <select name="kategori_id" id="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php while ($row = $kategori->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="judul" class="form-label text-white fw-bold">Judul Pengaduan</label>
        <input type="text" name="judul" id="judul" class="form-control" required
            placeholder="Contoh : Labor 308 Gedung E">
    </div>

    <div class="alert alert-warning">
        <strong>Berikan keterangan sedetail mungkin atau pengaduan bisa ditolak!</strong>
    </div>

    <div class="mb-3">
        <label for="isi" class="form-label text-white fw-bold">Isi Pengaduan</label>
        <textarea name="isi" id="isi" rows="6" class="form-control" required
            placeholder="Berikan detail keterangan disini serinci dan sejelasnya"></textarea>
    </div>

    <div class="mb-3">
        <label for="lampiran" class="form-label text-white fw-bold">Lampiran (Opsional)</label>
        <input type="file" name="lampiran" id="lampiran" class="form-control" accept=".jpg,.jpeg,.png">
    </div>

    <button type="submit" name="kirim" class="btn btn-warning text-white">Kirim Pengaduan</button>
</form>