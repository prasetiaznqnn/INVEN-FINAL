<?php
require "../backend/function.php";

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    // Jika belum login atau bukan admin, arahkan ke halaman login
    header("Location: ../index.php");
    exit();
}

// Proses selanjutnya jika sudah login


// Cek apakah pengguna sudah login dan memiliki role 'admin' atau 'user'
// if (!isset($_SESSION['id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
//     // Jika belum login atau bukan admin atau user, arahkan ke halaman login
//     header("Location: ../index.php");
//     exit();
// }
?>
<html>

<head>
    <title>Export Barang Masuk</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Data Barang</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../admin/masuk">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>

        <div class="data-tables datatable-dark">

            <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE BARANG</th>
                        <th>TANGGAL</th>
                        <th>SUPPLIER</th>
                        <th>JENIS BARANG</th>
                        <th>NAMA BARANG</th>
                        <th>MAKER</th>
                        <th>JUMLAH</th>
                        <th>NOTE</th>
                    </tr>
                </thead>
                <tbody>



                    <?php

                    $ambilsemuadatastock = mysqli_query($conn, "
                                    SELECT bm.*, mb.jenis_barang, mb.nama_barang, mb.maker 
                                    FROM barang_masuk bm
                                    LEFT JOIN master_barang mb ON bm.kode_barang = mb.kode_barang


                                    ");

                    $i = 1;
                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                        $kodebarang = $data['kode_barang'];
                        $tanggal = $data['tanggal_masuk'];
                        $supplier = $data['supplier'];
                        $jenisbarang = $data['jenis_barang'];
                        $namabarang = $data['nama_barang'];
                        $maker = $data['maker'];
                        $qty = $data['jumlah_masuk'];
                        $note = $data['note'];
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $kodebarang; ?></td>
                            <td><?= $tanggal; ?></td>
                            <td><?= $supplier; ?></td>
                            <td><?= $jenisbarang; ?></td>
                            <td><?= $namabarang; ?></td>
                            <td><?= $maker; ?></td>
                            <td><?= $qty; ?></td>
                            <td><?= $note; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>


            </table>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        title: 'Barang Masuk'
                    },
                    {
                        extend: 'pdf',
                        title: 'Barang Keluar',
                        customize: function(doc) {
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center';
                                });
                            });
                            doc.pageOrientation = 'landscape';
                            doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*'];
                            doc.content[1].layout = {
                                hLineWidth: function(i) {
                                    return 0.5;
                                },
                                vLineWidth: function(i) {
                                    return 0.5;
                                },
                                hLineColor: function(i) {
                                    return '#000';
                                },
                                vLineColor: function(i) {
                                    return '#000';
                                },
                                fillColor: function(i) {
                                    return (i === 0 || i === doc.content[1].table.body.length) ? '#ccc' : null;
                                }
                            };
                            // Tambahkan pengaturan agar tabel berada di tengah halaman
                            doc.content[1].margin = [0, 0, 0, 0]; // Hapus margin bawaan tabel
                            doc.content[1].alignment = 'center'; // Rata tengah seluruh tabel
                        }
                    },
                    'print'
                ]
            });
        });
    </script>

    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.buttons.min.js"></script>
    <script src="../js/buttons.flash.min.js"></script>
    <script src="../js/jszip.min.js"></script>
    <script src="../js/pdfmake.min.js"></script>
    <script src="../js/vfs_fonts.js"></script>
    <script src="../js/buttons.html5.min.js"></script>
    <script src="../js/buttons.print.min.js"></script>


</body>

</html>