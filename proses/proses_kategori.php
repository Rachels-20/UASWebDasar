<?php
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama_kategori']);

    $stmt = $koneksi->prepare("INSERT INTO rachel_kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama);
    $stmt->execute();

    header("Location: ../index.php?folder=kategori&page=data_kategori");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = trim($_POST['nama_kategori']);

    $stmt = $koneksi->prepare("UPDATE rachel_kategori SET nama_kategori=? WHERE id=?");
    $stmt->bind_param("si", $nama, $id);
    $stmt->execute();

    header("Location: ../index.php?folder=kategori&page=data_kategori");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $stmt = $koneksi->prepare("DELETE FROM rachel_kategori WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ../index.php?folder=kategori&page=data_kategori");
    exit;
}
