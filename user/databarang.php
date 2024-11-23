<?php
$title = "Data Barang | Elastomix";
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'user'
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    // Jika belum login atau bukan user, arahkan ke halaman login
    header("Location: ../index.php");
    exit();
}

// Proses selanjutnya jika sudah login
require "../BackEnd/function.php";
require "../header.php";
?>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Data Master</h1>


        <!-- tabel hasil data -->
        <div class="card mb-4 mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KODE BARANG</th>
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
                                    <td><?= $i++; ?></td>
                                    <td><?= $kbarang; ?></td>
                                    <td><?= $jenisBarang; ?></td>
                                    <td><?= $namabarang; ?></td>
                                    <td><?= $maker; ?></td>
                                    <td><?= $qty; ?></td>
                                    <td><?= $deskripsi; ?></td>
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
</div>
</div>

<?php include "../footer.php"; ?>