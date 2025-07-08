<?php
session_start();
include 'koneksi.php';

$folder = $_GET['folder'] ?? '';
$page   = $_GET['page'] ?? 'home';

// Proteksi login untuk semua halaman kecuali home dan pengaduan_terbaru
$halaman_terbuka = ['home', 'pengaduan_terbaru'];
if (!in_array($page, $halaman_terbuka) && !isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Judul dinamis
switch ($page) {
    case 'pengaduan_terbaru':
        $judul = 'Pengaduan Terbaru | LAPOR-PNP';
        break;
    case 'home':
        $judul = 'Home | LAPOR-PNP';
        break;
    case 'form_edit_tanggapan':
    case 'form_tanggapan':
    case 'tanggapan':
    case 'tanggapan_diproses':
    case 'tanggapan_selesai':
        $judul = 'Tanggapan | LAPOR-PNP';
        break;
    case 'status_laporan':
        $judul = 'Status Pengaduan | LAPOR-PNP';
        break;
    case 'buat_laporan':
        $judul = 'Buat Pengaduan | LAPOR-PNP';
        break;
    case 'detail_tanggapan':
    case 'detail_laporan':
        $judul = 'Detail Pengaduan | LAPOR-PNP';
        break;
    case 'daftar_pengguna':
    case 'data_kategori':
        $judul = ucfirst(str_replace("_", " ", $page)) . ' | LAPOR-PNP';
        break;
    default:
        $judul = 'LAPOR-PNP';
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $judul ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins:wght@600&display=swap"
        rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('assets/img/home.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        .nav-link {
            color: white !important;
            transition: 0.2s;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff !important;
        }

        .nav-link.active {
            background-color: white !important;
            color: #ff7f00 !important;
            font-weight: bold;
            border-radius: 0.5rem;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: 0.3s ease;
        }

        .table-transparent {
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            color: white;
        }

        .table-transparent th,
        .table-transparent td {
            background-color: transparent !important;
            color: white;
        }

        .table-orange thead {
            background-color: #ff7f00;
            color: white;
        }

        .backdrop-blur {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
        }

        footer {
            background-color: #ff7f00 !important;
        }

        .col-no {
            width: 5%;
            text-align: center;
            vertical-align: middle;
        }

        .judul-text-center {
            text-align: center;
            vertical-align: middle;
        }

        .btn-orange {
            background-color: #ff7f00;
            color: white;
            border: none;
        }

        .btn-orange:hover {
            background-color: #e66f00;
            color: white;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color:#ff7f00;" data-bs-theme="light">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2" href="index.php?page=home">
                <img src="assets/img/logopnp.png" width="36" alt="Logo PNP"> LAPOR-PNP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-2 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'home' ? 'active' : '' ?>" href="index.php?page=home">Home</a>
                    </li>

                    <?php if (!isset($_SESSION['login']) || $_SESSION['level'] != 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'pengaduan_terbaru' ? 'active' : '' ?>"
                                href="index.php?page=pengaduan_terbaru">Pengaduan Terbaru</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['login']) && $_SESSION['level'] == 'mahasiswa'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'buat_laporan' ? 'active' : '' ?>"
                                href="index.php?folder=mahasiswa&page=buat_laporan">Buat Pengaduan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'status_laporan' ? 'active' : '' ?>"
                                href="index.php?folder=mahasiswa&page=status_laporan">Status Pengaduan</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['login']) && $_SESSION['level'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $folder == 'kategori' ? 'active' : '' ?>"
                                href="index.php?folder=kategori&page=data_kategori">Kategori</a>
                        </li>
                        <?php $isTanggapan = in_array($page, ['tanggapan', 'tanggapan_diproses', 'tanggapan_selesai', 'form_edit_tanggapan', 'detail_tanggapan', 'form_tanggapan']); ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $isTanggapan ? 'active' : '' ?>"
                                href="index.php?folder=admin&page=tanggapan">Pengaduan & Tanggapan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'daftar_pengguna' ? 'active' : '' ?>"
                                href="index.php?folder=admin&page=daftar_pengguna">Pengguna</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['login'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= $_SESSION['nama'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"><?= $_SESSION['email'] ?></a></li>
                                <li><a class="dropdown-item"><?= $_SESSION['level'] ?></a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-outline-light">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- KONTEN -->
    <div class="container my-4" style="padding-top: 70px; padding-bottom: 60px">
        <?php
        $file = $folder ? "$folder/$page.php" : "$page.php";
        if (file_exists($file)) {
            include $file;
        } else {
            echo "<div class='alert alert-danger'>Halaman tidak tersedia.</div>";
        }
        ?>
    </div>

    <!-- FOOTER -->
    <footer class="text-center py-3 fixed-bottom border-top">
        <span class="text-black">Copyright &copy; <?= date('Y') ?> by <strong>Rachel Setiawan</strong></span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tambah efek bayangan navbar saat scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            nav.classList.toggle('scrolled', window.scrollY > 10);
        });
    </script>
</body>

</html>