<?php
require_once("function.php");
session_start();


$id_keluar = $_POST['id_keluar'];
$action = $_POST['action'];
$admin_name = $_SESSION['username']; // Simpan nama admin dari session

if ($action === 'approve') {
    // Update status menjadi approved
    $approveQuery = "UPDATE barang_keluar 
                     SET status_approve = 'approved', alasan = CONCAT('Disetujui oleh ', '$admin_name') 
                     WHERE id = '$id_keluar'";
    mysqli_query($conn, $approveQuery);

    // Update stok barang
    $barangQuery = mysqli_query($conn, "SELECT kode_barang, jumlah_keluar FROM barang_keluar WHERE id = '$id_keluar'");
    $barang = mysqli_fetch_assoc($barangQuery);
    $updateStok = "UPDATE master_barang 
                   SET jumlah = jumlah - {$barang['jumlah_keluar']} 
                   WHERE kode_barang = '{$barang['kode_barang']}'";
    mysqli_query($conn, $updateStok);
} elseif ($action === 'reject') {
    // Update status menjadi rejected dengan alasan
    $rejectQuery = "UPDATE barang_keluar 
                    SET status_approve = 'rejected', alasan = CONCAT('Barang ditolak oleh ', '$admin_name') 
                    WHERE id = '$id_keluar'";
    mysqli_query($conn, $rejectQuery);
}




// Redirect kembali ke halaman admin
header("Location: ../admin/keluar");
exit();
