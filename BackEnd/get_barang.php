<?php
include 'function.php'; 

if (isset($_POST['kode_barang'])) {
    $kode_barang = $_POST['kode_barang'];

    // Query untuk mengambil data barang berdasarkan kode_barang
    $query = "SELECT jenis_barang, nama_barang, maker FROM master_barang WHERE kode_barang = '$kode_barang'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data); 
    } else {
        echo json_encode([]);
    }
}
?>
