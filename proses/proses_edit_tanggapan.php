<?php
session_start();
require_once '../koneksi.php';

if ($_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pengaduan_id = intval($_POST['pengaduan_id']);
    $status = $_POST['status'];
    $catatan_selesai = trim($_POST['catatan_admin_selesai']);

    // Validasi
    if (empty($status) || empty($catatan_selesai)) {
        echo "<script>alert('Semua field wajib diisi.');history.back();</script>";
        exit;
    }

    // Update status pengaduan
    $koneksi->query("UPDATE rachel_pengaduan SET status = '$status', updated_at = NOW() WHERE id = $pengaduan_id");

    // Update catatan selesai tanggapan
    $stmt = $koneksi->prepare("UPDATE rachel_tanggapan SET catatan_admin_selesai = ?, tanggal = NOW() WHERE pengaduan_id = ?");
    $stmt->bind_param("si", $catatan_selesai, $pengaduan_id);
    $stmt->execute();

    header("Location: ../index.php?folder=admin&page=tanggapan_selesai");
    exit;
}
