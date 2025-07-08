<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "db_lapor_pnp";

$koneksi = new mysqli($server, $user, $password, $db);

if ($koneksi->connect_error) {
    die("Koneksi error : " . $koneksi->connect_error);
}