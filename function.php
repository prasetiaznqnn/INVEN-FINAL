<?php
$conn = mysqli_connect("localhost", "root", "", "db_inventory");

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Cek jika form disubmit INDEX.PHP
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

            // Query untuk menyimpan data ke database
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
        header("Location: index.php");
        exit();
    }


    // Redirect setelah berhasil
    header("Location: index.php");
    exit();
}

// MASUK.PHP
if (isset($_POST['addnewbarangmasuk'])) {
    // Ambil data dari form
    $tanggal = $_POST['tanggal'];
    $supplier = $_POST['supplier'];
    $kodebarang = $_POST['kodebarang'];
    $jumlah = $_POST['jumlah'];
    $note = $_POST['note'];

    // Loop untuk menyimpan setiap item ke database
    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $qty = mysqli_real_escape_string($conn, $jumlah[$i]);
        $catatan = mysqli_real_escape_string($conn, $note[$i]);

        // Perbarui query untuk menyimpan data barang masuk
        $queryMasuk = "INSERT INTO barang_masuk (tanggal_masuk, supplier, kode_barang, jumlah_masuk, note) VALUES ('$tanggal', '$supplier', '$kode', '$qty', '$catatan')";

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

    // Redirect setelah berhasil
    header("Location: masuk.php");
    exit();
}


// FORM DI KELUAR.PHP
if (isset($_POST['addbarangkeluar'])) {
    $tanggal = $_POST['tanggal'];
    $user = $_POST['user'];
    $kodebarang = $_POST['kodebarang'];
    $jumlah = $_POST['jumlah'];
    $note = $_POST['note'];

    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $qty = mysqli_real_escape_string($conn, $jumlah[$i]);
        $catatan = mysqli_real_escape_string($conn, $note[$i]);

        // Perbarui query untuk menyimpan data barang keluar
        $queryKeluar = "INSERT INTO barang_keluar (tanggal_keluar, user, kode_barang, jumlah_keluar, note) VALUES ('$tanggal', '$user', '$kode', '$qty', '$catatan')";

        // Eksekusi query untuk barang keluar
        if (mysqli_query($conn, $queryKeluar)) {
            // Update jumlah di tabel master_barang
            $updateQuery = "UPDATE master_barang SET jumlah = jumlah - $qty WHERE kode_barang = '$kode'";
            mysqli_query($conn, $updateQuery);
        } else {
            // Handle error
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Redirect setelah berhasil
    header("Location: keluar.php");
    exit();
}

function cekStok($conn, $kode_barang)
{
    $query = "SELECT stok FROM master_barang WHERE kode_barang = '$kode_barang'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['stok'];
}

// FORM DI KELUAR.PHP
if (isset($_POST['addbarangkeluar'])) {
    $tanggal = $_POST['tanggal'];
    $user = $_POST['user'];

    // Loop through semua item
    $kode_barang_array = $_POST['kodebarang'];
    $jumlah_array = $_POST['jumlah'];
    $note_array = $_POST['note'];

    $success = true;
    $error_message = "";

    // Validasi stok untuk semua item sebelum melakukan insert
    for ($i = 0; $i < count($kode_barang_array); $i++) {
        $kode_barang = $kode_barang_array[$i];
        $jumlah = $jumlah_array[$i];

        $stok_tersedia = cekStok($conn, $kode_barang);

        // Cek jika jumlah yang diminta lebih besar dari stok yang tersedia
        if ($jumlah > $stok_tersedia) {
            $success = false;
            $error_message .= "Stok tidak mencukupi untuk kode barang $kode_barang. Stok tersedia: $stok_tersedia, Permintaan: $jumlah\\n";
        }
    }

    // Jika semua stok mencukupi, lakukan insert
    if ($success) {
        $allInserted = true; // Flag untuk memeriksa semua insert
        for ($i = 0; $i < count($kode_barang_array); $i++) {
            $kode_barang = $kode_barang_array[$i];
            $jumlah = $jumlah_array[$i];
            $note = $note_array[$i];

            // Insert ke barang_keluar
            $addtotable = mysqli_query($conn, "INSERT INTO barang_keluar (kode_barang, tanggal_keluar, user, jumlah_keluar, note) VALUES('$kode_barang', '$tanggal', '$user', '$jumlah', '$note')");

            // Update stok di master_barang
            $updatestok = mysqli_query($conn, "UPDATE master_barang SET stok = stok - $jumlah WHERE kode_barang = '$kode_barang'");

            // Cek jika salah satu query gagal
            if (!$addtotable || !$updatestok) {
                $allInserted = false; // Set flag ke false jika ada yang gagal
                break; // Keluar dari loop jika ada kesalahan
            }
        }

        if ($allInserted) {
            echo '
            <script>
                alert("Barang berhasil dikeluarkan");
                window.location.href="keluar.php";
            </script>
            ';
        } else {
            echo '
            <script>
                alert("Gagal mengeluarkan barang");
                window.location.href="keluar.php";
            </script>
            ';
        }
    } else {
        echo "
        <script>
            alert('$error_message');
            window.location.href='keluar.php';
        </script>
        ";
    }
}
