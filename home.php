<?php
$level = $_SESSION['level'] ?? null;

if ($level === 'admin') {
    $jml_user      = $koneksi->query("SELECT COUNT(*) as total FROM rachel_users")->fetch_assoc()['total'];
    $jml_pengaduan = $koneksi->query("SELECT COUNT(*) as total FROM rachel_pengaduan")->fetch_assoc()['total'];
    $jml_kategori  = $koneksi->query("SELECT COUNT(*) as total FROM rachel_kategori")->fetch_assoc()['total'];
    $jml_tanggapan = $koneksi->query("SELECT COUNT(*) as total FROM rachel_tanggapan")->fetch_assoc()['total'];
}
?>

<style>
    .card-statistik {
        background-color: rgb(61, 61, 61, 0.5);
        /* sangat transparan */
        border: 1.5px solid #ffffff;
        /* putih */
        border-radius: 12px;
        color: white;
        text-align: center;
        padding: 20px 15px;
        min-height: 110px;
        box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
        transition: transform 0.2s ease;
    }

    .card-statistik:hover {
        transform: translateY(-3px);
    }

    .card-statistik h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .card-statistik h3 {
        font-size: 1.8rem;
        font-weight: bold;
        color: white;
        margin-bottom: 0;
    }
</style>

<div class="overlay-content">
    <?php if ($level === 'admin'): ?>
        <div class="container text-center text-white mb-4">
            <h1 class="fw-bold text-uppercase mb-3">Selamat Datang, Admin LAPOR-PNP</h1>
            <p class="lead">
                Terima kasih telah menjadi bagian penting dalam pengelolaan aspirasi dan pengaduan mahasiswa. <br>
                Gunakan sistem ini untuk memantau laporan, memberikan tanggapan, dan mengelola kategori secara efisien.
            </p>
        </div>


        <div class="container">
            <div class="row g-4 justify-content-center">
                <!-- Card Pengguna -->
                <div class="col-6 col-md-3">
                    <a href="index.php?folder=admin&page=daftar_pengguna" class="text-decoration-none">
                        <div class="card-statistik">
                            <h6>Total Pengguna</h6>
                            <h3><?= $jml_user ?></h3>
                        </div>
                    </a>
                </div>


                <!-- Card Pengaduan -->
                <div class="col-6 col-md-3">
                    <a href="index.php?folder=admin&page=tanggapan" class="text-decoration-none">
                        <div class="card-statistik">
                            <h6>Total Pengaduan</h6>
                            <h3><?= $jml_pengaduan ?></h3>
                        </div>
                    </a>
                </div>

                <!-- Card Kategori -->
                <div class="col-6 col-md-3">
                    <a href="index.php?folder=kategori&page=data_kategori" class="text-decoration-none">
                        <div class="card-statistik">
                            <h6>Total Kategori</h6>
                            <h3><?= $jml_kategori ?></h3>
                        </div>
                    </a>
                </div>

                <!-- Card Tanggapan -->
                <div class="col-6 col-md-3">
                    <a href="index.php?folder=admin&page=tanggapan" class="text-decoration-none">
                        <div class="card-statistik">
                            <h6>Total Tanggapan</h6>
                            <h3><?= $jml_tanggapan ?></h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="container text-center mt-5">
            <hr class="bg-white opacity-50 w-50 mx-auto" hr style="border-top: 2px solid white; width: 50%;">
            <p class="fst-italic text-white-50 text-center">
                “Pelayanan yang cepat dan tanggap adalah kunci kepercayaan. <br>
                Jadilah penggerak perubahan untuk kampus yang lebih baik.”
            </p>
        </div>

    <?php else: ?>
        <!-- Tampilan umum untuk mahasiswa -->
        <div class="container text-center text-white">
            <h1 class="fw-bold text-uppercase">Selamat Datang di LAPOR-PNP</h1>
            <h3 class="fw-semibold">Sistem Layanan Aspirasi dan Pengaduan Online Rakyat-Politeknik Negeri Padang</h3>
            <p class="lead mt-3">
                LAPOR-PNP adalah jembatan komunikasi antara mahasiswa dan kampus. Sampaikan aspirasi, kritik, maupun saran
                Anda secara aman dan terstruktur untuk mewujudkan perubahan yang nyata.
            </p>

            <div class="row justify-content-center mt-5">
                <!-- Buat Laporan -->
                <div class="col-md-4 mb-4">
                    <div class="card border-light shadow-lg bg-transparent backdrop-blur">
                        <div class="card-body text-center">
                            <h4 class="fw-bold text-white"><i class="bi bi-pencil-square me-1"></i> Buat Pengaduan</h4>

                            <p class="text-light">Ajukan pengaduan atau aspirasi mengenai kegiatan kampus.</p>
                            <a href="index.php?folder=mahasiswa&page=buat_laporan" class="btn btn-warning text-white">Buat
                                Laporan</a>
                        </div>
                    </div>
                </div>

                <!-- Cek Status -->
                <div class="col-md-4 mb-4">
                    <div class="card border-light shadow-lg bg-transparent backdrop-blur">
                        <div class="card-body text-center">
                            <h4 class="fw-bold text-white"><i class="bi bi-search me-1"></i> Lihat Status</h4>
                            <p class="text-light">Pantau proses pengaduan Anda secara langsung dan transparan.</p>
                            <a href="index.php?folder=mahasiswa&page=status_laporan" class="btn btn-warning text-white">Cek
                                Status</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <small class="text-white-50">
                    Sistem ini dikembangkan untuk menunjang keterbukaan informasi dan transparansi pelayanan akademik
                    di lingkungan Politeknik Negeri Padang.
                </small>
            </div>

            <div class="container text-center mt-3">
                <hr class="w-50 mx-auto" style="border-top: 2px solid white;">
                <p class="fst-italic text-white-50">
                    “Aspirasi kecil bisa berdampak besar. Suaramu berarti untuk perubahan kampus.”
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>