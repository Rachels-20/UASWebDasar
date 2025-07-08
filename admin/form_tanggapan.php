<?php
// Cek hak akses
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

// Validasi ID laporan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-warning text-white'>ID pengaduan tidak valid.</div>";
    exit;
}

$id = intval($_GET['id']);

// Ambil detail pengaduan
$query = "
    SELECT p.*, u.nama AS nama_user, k.nama_kategori
    FROM rachel_pengaduan p
    JOIN rachel_users u ON p.user_id = u.id
    JOIN rachel_kategori k ON p.kategori_id = k.id
    WHERE p.id = ?
";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning text-white'>Pengaduan tidak ditemukan.</div>";
    exit;
}

$pengaduan = $result->fetch_assoc();
?>

<h3 class="mb-4 fw-bold text-white">Tanggapi Pengaduan</h3>

<table class="table table-bordered table-transparent text-white">
    <tr>
        <th>Nama Pengguna</th>
        <td><?= htmlspecialchars($pengaduan['nama_user']) ?></td>
    </tr>
    <tr>
        <th>Judul</th>
        <td><?= htmlspecialchars($pengaduan['judul']) ?></td>
    </tr>
    <tr>
        <th>Kategori</th>
        <td><?= htmlspecialchars($pengaduan['nama_kategori']) ?></td>
    </tr>
    <tr>
        <th>Isi Laporan</th>
        <td><?= nl2br(htmlspecialchars($pengaduan['isi'])) ?></td>
    </tr>
    <tr>
        <th>Status Saat Ini</th>
        <td><span class="badge bg-secondary"><?= $pengaduan['status'] ?></span></td>
    </tr>
    <tr>
        <th>Lampiran</th>
        <td>
            <?php if (!empty($pengaduan['lampiran'])): ?>
                <a href="uploads/<?= htmlspecialchars($pengaduan['lampiran']) ?>" target="_blank" class="text-white">
                    Lihat Lampiran
                </a>
            <?php else: ?>
                <span class="text-white">Tidak ada lampiran</span>
            <?php endif; ?>
        </td>
    </tr>

</table>

<!-- Form -->
<form action="proses/proses_tanggapan.php" method="POST" class="mt-4">
    <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">

    <!-- Pilih Status -->
    <div class="mb-3">
        <label for="status" class="form-label text-white">Ubah Status Laporan</label>
        <select name="status" id="status" class="form-select" required onchange="toggleTextarea()">
            <option value="">-- Pilih Status --</option>
            <option value="Diproses">Diproses</option>
            <option value="Selesai">Selesai</option>
            <option value="Ditolak">Ditolak</option>
        </select>
    </div>

    <!-- Textarea Dinamis -->
    <div class="mb-3 d-none" id="keterangan_wrapper">
        <label for="keterangan" class="form-label text-white" id="keterangan_label"></label>
        <textarea name="keterangan" id="keterangan" rows="4" class="form-control"></textarea>
    </div>

    <!-- Tombol -->
    <button type="submit" name="kirim" class="btn btn-success">Kirim Tanggapan</button>
    <a href="index.php?folder=admin&page=tanggapan" class="btn btn-warning">Kembali</a>
</form>

<script>
    function toggleTextarea() {
        const status = document.getElementById('status').value;
        const wrapper = document.getElementById('keterangan_wrapper');
        const label = document.getElementById('keterangan_label');

        wrapper.classList.remove('d-none');

        if (status === 'Diproses') {
            label.innerText = 'Catatan Admin saat Diproses (opsional)';
        } else if (status === 'Selesai') {
            label.innerText = 'Catatan Admin saat Selesai (opsional)';
        } else if (status === 'Ditolak') {
            label.innerText = 'Alasan Penolakan (opsional)';
        } else {
            wrapper.classList.add('d-none');
            label.innerText = '';
        }
    }
</script>