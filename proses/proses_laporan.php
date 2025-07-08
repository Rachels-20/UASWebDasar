<?php
session_start();
include '../koneksi.php';

// Pastikan user sudah login dan mahasiswa
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

// Ambil email dari session
$email = $_SESSION['email'];

// Cari user_id dari database
$getUser = $koneksi->prepare("SELECT id FROM rachel_users WHERE email = ?");
$getUser->bind_param("s", $email);
$getUser->execute();
$result = $getUser->get_result();

if ($result->num_rows !== 1) {
    die("Pengguna tidak ditemukan.");
}

$user = $result->fetch_assoc();
$user_id = $user['id'];

// Tangkap data dari form
$kategori_id = $_POST['kategori_id'];
$judul = $_POST['judul'];
$isi = $_POST['isi'];
$lampiran = null;

// Proses upload jika ada file
if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === 0) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $fileType = mime_content_type($_FILES['lampiran']['tmp_name']);
    $fileSize = $_FILES['lampiran']['size'];

    if (!in_array($fileType, $allowedTypes)) {
        echo "<script>alert('File harus berupa gambar JPG, JPEG, atau PNG');history.back();</script>";
        exit;
    }

    if ($fileSize > $maxSize) {
        echo "<script>alert('Ukuran file maksimal 2MB');history.back();</script>";
        exit;
    }

    $nama_file = uniqid() . '_' . basename($_FILES['lampiran']['name']);
    $tujuan = '../uploads/' . $nama_file;

    if (move_uploaded_file($_FILES['lampiran']['tmp_name'], $tujuan)) {
        $lampiran = $nama_file;
    } else {
        echo "<script>alert('Gagal mengunggah file.');history.back();</script>";
        exit;
    }
}

// Simpan laporan ke database
$stmt = $koneksi->prepare("INSERT INTO rachel_pengaduan (user_id, kategori_id, judul, isi, lampiran, status) 
                           VALUES (?, ?, ?, ?, ?, 'Menunggu')");
$stmt->bind_param("iisss", $user_id, $kategori_id, $judul, $isi, $lampiran);
$stmt->execute();

// Redirect ke status laporan
header("Location: ../index.php?folder=mahasiswa&page=status_laporan");
exit;
