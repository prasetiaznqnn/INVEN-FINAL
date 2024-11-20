<?php
require "function.php";
?>
<html>

<head>
    <title>Stock Barang</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2>Data Barang</h2>
        <h4>(Inventory)</h4>
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
                            doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*']; // Set widths for columns
                            doc.content[1].layout = {
                                hLineWidth: function(i) {
                                    return 0.5; // Border width
                                },
                                vLineWidth: function(i) {
                                    return 0.5; // Border width
                                },
                                hLineColor: function(i) {
                                    return '#000'; // Border color
                                },
                                vLineColor: function(i) {
                                    return '#000'; // Border color
                                },
                                fillColor: function(i) {
                                    return (i === 0 || i === doc.content[1].table.body.length) ? '#ccc' : null; // Header color
                                }
                            };
                        }
                    },
                    'print'
                ]
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>



</body>

</html>