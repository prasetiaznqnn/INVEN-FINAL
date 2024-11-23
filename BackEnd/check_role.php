<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../index");
    exit();
}

// Periksa role admin
if ($_SESSION['role'] !== 'admin') {
    // Jika bukan admin, arahkan ke halaman utama user
    header("Location: ../user/index");
    exit();
}


// Jika admin, biarkan proses lanjut
