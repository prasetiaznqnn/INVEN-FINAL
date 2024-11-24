<?php
$conn = mysqli_connect("localhost", "root", "", "db_inventory");

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// FORM LOGIN



// FORM LOGIN SELESAI

// FORM DI BARANG.PHP
if (isset($_POST['addnewbarang'])) {
    // Ambil data dari form
    $kodebarang = $_POST['kodebarang'];
    $jenisbarang = $_POST['jenisbarang'];
    $namabarang = $_POST['namabarang'];
    $dekripsi = $_POST['dekripsi'];
    $maker = $_POST['maker'];
    $errors = [];


    // Loop untuk menyimpan setiap item ke database
    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $jenis = mysqli_real_escape_string($conn, $jenisbarang[$i]);
        $nama = mysqli_real_escape_string($conn, $namabarang[$i]);
        $deskripsi = mysqli_real_escape_string($conn, $dekripsi[$i]);
        $makerName = mysqli_real_escape_string($conn, $maker[$i]);

        // Cek apakah kode barang sudah ada
        $checkQuery = "SELECT * FROM master_barang WHERE kode_barang = '$kode'";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {

            // Jika kode barang sudah ada, tambahkan pesan kesalahan
            $errors[] = "Kode barang '$kode' sudah ada. Silakan gunakan kode barang yang berbeda.";
        } else {

            // fungsi untuk menyimpan data ke database
            $query = "INSERT INTO master_barang (kode_barang, jenis_barang, nama_barang, deskripsi, maker) VALUES ('$kode', '$jenis', '$nama', '$deskripsi', '$makerName')";

            // Eksekusi query
            if (!mysqli_query($conn, $query)) {
                $errors[] = "Error: " . mysqli_error($conn);
            }
        }
    }
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            header("Location: ../admin/databarang");
        }
        exit();
    }
    // backlink setelah berhasil
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        header("Location: ../admin/databarang");
    } else {
        header("Location: ../admin/databarang");
    }
    exit();
}


//FORM DI BARANG MASUK.PHP
if (isset($_POST['addnewbarangmasuk'])) {
    // Ambil data dari form
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
    $kodebarang = $_POST['kodebarang'];
    $jumlah = $_POST['jumlah'];
    $note = $_POST['note'];

    // Loop untuk menyimpan setiap item ke database
    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $qty = mysqli_real_escape_string($conn, $jumlah[$i]);
        $catatan = mysqli_real_escape_string($conn, $note[$i]);

        // Perbarui query untuk menyimpan data barang masuk
        $queryMasuk = "INSERT INTO barang_masuk (tanggal_masuk, supplier, kode_barang, jumlah_masuk, note, kode_masuk) VALUES ('$tanggal', '$supplier', '$kode', '$qty', '$catatan', '')";

        // Eksekusi query untuk barang masuk
        if (mysqli_query($conn, $queryMasuk)) {
            // Update jumlah di tabel master_barang
            $updateQuery = "UPDATE master_barang SET jumlah = jumlah + $qty WHERE kode_barang = '$kode'";
            mysqli_query($conn, $updateQuery);
        } else {
            // Handle error
            echo "Error: " . mysqli_error($conn);
        }
    }

    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/masuk");
    } else {
        header("Location: ../user/masuk");
    }
    exit();
}
// FORM BARANG MASUK SELESAI        

// FORM DI KELUAR.PHP
if (isset($_POST['addbarangkeluar'])) {
    session_start();
    $tanggal = $_POST['tanggal'];
    $user = $_POST['user'];
    $kodebarang = $_POST['kodebarang'];
    $jumlah = $_POST['jumlah'];
    $note = $_POST['note'];
    $created_by = $_SESSION['username']; // Ambil username dari session

    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $qty = mysqli_real_escape_string($conn, $jumlah[$i]);
        $catatan = mysqli_real_escape_string($conn, $note[$i]);

        // Simpan dengan status pending
        $queryKeluar = "INSERT INTO barang_keluar 
                        (tanggal_keluar, user, kode_barang, jumlah_keluar, note, kode_transaksi, status_approve, created_by) 
                        VALUES 
                        ('$tanggal', '$user', '$kode', '$qty', '$catatan', '', 'pending', '$created_by')";

        if (!mysqli_query($conn, $queryKeluar)) {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/keluar");
    } else {
        header("Location: ../user/keluar");
    }
    exit();
}


// Fungsi Untuk APPROVED BY 
if (isset($_POST['approveBarangKeluar'])) {
    $id_keluar = $_POST['id_keluar'];
    $kode_barang = $_POST['kode_barang'];
    $jumlah = $_POST['jumlah'];

    // Update status approve di tabel barang_keluar
    $approveQuery = "UPDATE barang_keluar SET status_approve = 'approved' WHERE id = '$id_keluar'";
    if (mysqli_query($conn, $approveQuery)) {
        // Kurangi stok barang di tabel master_barang
        $updateQuery = "UPDATE master_barang SET jumlah = jumlah - $jumlah WHERE kode_barang = '$kode_barang'";
        mysqli_query($conn, $updateQuery);

        // Redirect kembali ke halaman persetujuan
        header("Location: ../admin/keluar");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


// FORM BARANG KELUAR SELESAI
