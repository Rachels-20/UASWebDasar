<?php
// PENGADUAN TERBARU
$queryTerbaru = "
SELECT p.id, p.judul, p.created_at
FROM rachel_pengaduan p
ORDER BY p.created_at DESC
LIMIT 25
";
$terbaru = $koneksi->query($queryTerbaru);
?>


<h3 class="mb-4 fw-bold text-white">Beberapa Pengaduan Terbaru</h3>

<?php if ($terbaru->num_rows > 0): ?>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-striped table-transparent table-orange">
            <thead class="table-warning">
                <tr>
                    <th class="col-no">No</th>
                    <th>Judul</th>
                    <th class="judul-text-center">Tanggal Pengaduan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $terbaru->fetch_assoc()): ?>
                    <tr>
                        <td class="col-no"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="judul-text-center"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>