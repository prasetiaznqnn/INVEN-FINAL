<?php
$title = "DashBoard | Elastomix";
require "../BackEnd/function.php";
require "../header.php";
require "../BackEnd/check_role.php"; // Pastikan file ini dipanggil
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">List Data Item</h1>
        <div class="card mb-4">
            <div class="card-header ">
                <i class="fas fa-table me-1"></i>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                            $ambilsemuastock = mysqli_query($conn, "SELECT master_barang.kode_barang, master_barang.nama_barang, master_barang.jenis_barang, 
                            COALESCE(SUM(barang_masuk.jumlah_masuk), 0) AS jumlah_masuk, 
                            COALESCE(SUM(CASE WHEN barang_keluar.status_approve = 'approved' THEN barang_keluar.jumlah_keluar ELSE 0 END), 0) AS jumlah_keluar, 
                            master_barang.jumlah AS stok 
                            FROM master_barang 
                            LEFT JOIN barang_masuk ON master_barang.kode_barang = barang_masuk.kode_barang 
                            LEFT JOIN barang_keluar ON master_barang.kode_barang = barang_keluar.kode_barang 
                            GROUP BY master_barang.kode_barang
                            ");
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
    </div>
</main>

<!-- Tambahkan bagian untuk menampilkan chart atau view lainnya -->

<?php
require "../footer.php"
?>