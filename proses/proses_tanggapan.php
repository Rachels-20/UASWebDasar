<?php
session_start();
include '../koneksi.php';

// Pastikan yang mengakses adalah admin
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['kirim'])) {
    $pengaduan_id = intval($_POST['pengaduan_id']);
    $status       = $_POST['status'];
    $keterangan   = trim($_POST['keterangan']);

    // Ambil ID admin dari session email
    $email = $_SESSION['email'];
    $responder = $koneksi->query("SELECT id FROM rachel_users WHERE email = '$email'")->fetch_assoc();
    $responder_id = $responder['id'] ?? null;

    // Validasi input
    if (empty($status) || !$responder_id) {
        echo "<script>alert('Status dan identitas admin wajib diisi.');history.back();</script>";
        exit;
    }

    // Inisialisasi kolom tanggapan
    $catatan_admin_proses = null;
    $catatan_admin_selesai = null;

    // Isi catatan sesuai status
    if ($status === 'Diproses') {
        $catatan_admin_proses = $keterangan;
    } elseif ($status === 'Selesai' || $status === 'Ditolak') {
        $catatan_admin_selesai = $keterangan;
    }

    // Simpan ke tabel rachel_tanggapan
    $stmt = $koneksi->prepare("INSERT INTO rachel_tanggapan 
        (pengaduan_id, responder_id, catatan_admin_proses, catatan_admin_selesai, tanggal) 
        VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $pengaduan_id, $responder_id, $catatan_admin_proses, $catatan_admin_selesai);
    $stmt->execute();

    // Update status pengaduan
    $stmt2 = $koneksi->prepare("UPDATE rachel_pengaduan SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt2->bind_param("si", $status, $pengaduan_id);
    $stmt2->execute();

    header("Location: ../index.php?folder=admin&page=tanggapan");
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
