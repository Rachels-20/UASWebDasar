<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi Mahasiswa | LAPOR-PNP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <h2 class="mb-4 text-center">Form Registrasi Mahasiswa</h2>

                <form action="proses/proses_register.php" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="daftar" class="btn btn-warning text-white">Daftar</button>
                        <a href="login.php" class="btn btn-secondary">Kembali ke Login</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>