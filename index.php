<?php
require "function.php";
require "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Inventory</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    </nav>

    </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Data Master Barang</h1>
                <?php
                session_start();
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    unset($_SESSION['errors']);
                } ?>
                <!-- Button trigger modal -->
                <button type="button" style="border-radius: 10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-folder-plus"></i>
                    Tambah Master Barang
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl"> <!-- Menggunakan kelas modal-xl di sini -->
                        <div class="modal-content  ">
                            <!-- HEADER -->
                            <div class="modal-header bg-primary">
                                <h1 class="modal-title fs-6 text-white" id="exampleModalLabel">Form Tambah Master Barang</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form id="dynamicForm" action="function.php" method="POST">
                                    <div id="formInputs">
                                        <div class="form-group mb-3 d-flex">
                                            <input type="text" name="kodebarang[]" placeholder="Kode Barang" class="form-control me-2" required>
                                            <input type="text" name="jenisbarang[]" placeholder="Jenis Barang" class="form-control me-2" required>
                                            <input type="text" name="namabarang[]" placeholder="Nama Barang" class="form-control me-2" required>
                                            <input type="text" name="dekripsi[]" placeholder="Deskripsi barang" class="form-control me-2" required>
                                            <input type="text" name="maker[]" placeholder="Maker" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success" id="addButton"><i class="bi bi-bag-plus me-1"></i>Tambah Item</button>
                                    <button type="button" class="btn btn-danger"><i class="bi bi-trash3"> Hapus Item</i></button>
                                    <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    document.getElementById('dynamicForm').addEventListener('submit', function(event) {
                        let isValid = true;
                        const inputs = document.querySelectorAll('#formInputs input');

                        inputs.forEach(input => {
                            if (input.value.trim() === '') {
                                isValid = false;
                                input.classList.add('is-invalid');
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        });

                        if (!isValid) {
                            event.preventDefault();
                            alert('Semua field harus diisi.');
                        }
                    });


                    // Tambah Barang
                    document.getElementById('addButton').addEventListener('click', function() {
                        const formInputs = document.getElementById('formInputs');
                        const newInputGroup = document.createElement('div');
                        newInputGroup.className = 'form-group mb-3 d-flex';
                        newInputGroup.innerHTML = `
                            <input type="text" name="kodebarang[]" placeholder="Kode Barang" class="form-control me-2">
                            <input type="text" name="jenisbarang[]" placeholder="Jenis Barang" class="form-control me-2">
                            <input type="text" name="namabarang[]" placeholder="Nama Barang" class="form-control me-2">
                            <input type="text" name="dekripsi[]" placeholder="Deskripsi barang" class="form-control me-2">
                            <input type="text" name="maker[]" placeholder="Maker" class="form-control">`;
                        formInputs.appendChild(newInputGroup);
                    });

                    // Hapus Item
                    document.querySelector('.btn-danger').addEventListener('click', function() {
                        const formInputs = document.getElementById('formInputs');
                        const inputGroups = formInputs.getElementsByClassName('form-group mb-3');

                        // Hapus grup input terakhir jika ada
                        if (inputGroups.length > 1) {
                            formInputs.removeChild(inputGroups[inputGroups.length - 1]);
                        } else {
                            alert("Tidak ada item untuk dihapus!");
                        }
                    });
                </script>

                <a href="export.php" style="color: white; text-decoration: none;"> <button style="border-radius: 10px;" class="btn btn-success">
                        Ekspor ke Data
                    </button></a>


                <!-- tabel hasil data -->
                <div class="card mb-4">

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
    <!-- MEMANGGIL FUNGSI SCRIPT -->





    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>