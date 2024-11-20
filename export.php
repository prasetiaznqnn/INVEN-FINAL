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



            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KODE BARANG</th>
                                </th>
                                <th>JENIS BARANG</th>
                                <th>NAMA BARANG</th>
                                <th>MAKER</th>
                                <th>JUMLAH</th>
                                <th>DESKRIPSI</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambilsemuastock = mysqli_query($conn, "SELECT * FROM  master_barang");
                            $i = 1;
                            while ($data = mysqli_fetch_array($ambilsemuastock)) {
                                $jenisBarang = $data['jenis_barang'];
                                $kbarang = $data['kode_barang'];
                                $namabarang = $data['nama_barang'];
                                $deskripsi = $data['deskripsi'];
                                $maker = $data['maker'];
                                $qty = $data['jumlah'];

                            ?>
                                <tr>
                                    <td class="text-center"><?= $i++; ?></td>
                                    <td class="text-center"><?= $kbarang; ?></td>
                                    <td class="text-center"><?= $jenisBarang; ?></td>
                                    <td class="text-center"><?= $namabarang; ?></td>
                                    <td class="text-center"><?= $maker; ?></td>
                                    <td class="text-center"><?= $qty; ?></td>
                                    <td class="text-center"><?= $deskripsi; ?></td>


                                </tr>
                            <?php
                            };
                            ?>
                        </tbody>
                    </table>
                </div>
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
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center';
                                });
                            });
                            doc.pageOrientation = 'landscape';
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