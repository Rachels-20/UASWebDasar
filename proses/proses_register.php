<?php
session_start();
include '../koneksi.php';

if (isset($_POST['daftar'])) {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah digunakan
    $cek = $koneksi->prepare("SELECT id FROM rachel_users WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Email sudah terdaftar.');
            window.location.href = '../register.php';
        </script>";
        exit;
    }

    // Simpan user baru (level: mahasiswa)
    $stmt = $koneksi->prepare("INSERT INTO rachel_users (nama, email, password, level) VALUES (?, ?, ?, 'mahasiswa')");
    $stmt->bind_param("sss", $nama, $email, $password);
    $stmt->execute();

    echo "<script>
        alert('Registrasi berhasil, silakan login!');
        window.location.href = '../login.php';
    </script>";
    exit;
} else {
    header("Location: ../register.php");
    exit;
}
