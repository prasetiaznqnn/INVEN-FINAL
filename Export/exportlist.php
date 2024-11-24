<?php
require "../BackEnd/function.php";
session_start();

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

// Proses selanjutnya jika sudah login
?>
<html>

<head>
    <title>Export Data Barang</title>
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
            <li class="breadcrumb-item"><a href="../admin/databarang">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>
        <div class="data-tables datatable-dark">
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>KODE BARANG</th>
                            <th>JENIS BARANG</th>
                            <th>NAMA BARANG</th>
                            <th>IN ITEM</th>
                            <th>OUT ITEM</th>
                            <th>STOK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ambilsemuastock = mysqli_query($conn, "SELECT master_barang.kode_barang, master_barang.nama_barang, master_barang.jenis_barang, COALESCE(SUM(barang_masuk.jumlah_masuk), 0) AS jumlah_masuk, COALESCE(SUM(barang_keluar.jumlah_keluar), 0) AS jumlah_keluar, master_barang.jumlah AS stok FROM master_barang LEFT JOIN barang_masuk ON master_barang.kode_barang = barang_masuk.kode_barang LEFT JOIN barang_keluar ON master_barang.kode_barang = barang_keluar.kode_barang GROUP BY master_barang.kode_barang");
                        $i = 1;
                        while ($data = mysqli_fetch_array($ambilsemuastock)) {
                            $kbarang = $data['kode_barang'];
                            $namabarang = $data['nama_barang'];
                            $jenisBarang = $data['jenis_barang'];
                            $jumlah_masuk = $data['jumlah_masuk'];
                            $jumlah_keluar = $data['jumlah_keluar'];
                            $stok = $data['stok'];
                        ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $kbarang; ?></td>
                                <td><?= $jenisBarang; ?></td>
                                <td><?= $namabarang; ?></td>
                                <td><?= $jumlah_masuk; ?></td>
                                <td><?= $jumlah_keluar; ?></td>
                                <td><?= $stok; ?></td>
                            </tr>
                        <?php
                        };
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        title: 'Data Barang',
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        }
                    },


                    {
                        extend: 'pdf',
                        title: 'Data Barang',
                        customize: function(doc) {
                            // Atur konten dokumen PDF
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center';
                                });
                            });

                            // Atur orientasi halaman menjadi landscape
                            doc.pageOrientation = 'landscape';

                            // Atur margin kiri dan kanan untuk memposisikan tabel di tengah
                            doc.pageMargins = [30, 30, 30, 30];

                            // Atur style untuk judul
                            doc.content[0].alignment = 'center';
                            doc.content[0].margin = [0, 0, 0, 20];

                            // Atur style untuk tabel
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
                            doc.content[1].margin = [0, 0, 0, 0];

                            // Tambahkan properti layout untuk memperbaiki garis tabel
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
                                paddingLeft: function(i) {
                                    return 4;
                                },
                                paddingRight: function(i) {
                                    return 4;
                                },
                                paddingTop: function(i) {
                                    return 4;
                                },
                                paddingBottom: function(i) {
                                    return 4;
                                }
                            };

                            // Atur agar tabel berada di tengah halaman
                            doc.defaultStyle = {
                                alignment: 'center'
                            };
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