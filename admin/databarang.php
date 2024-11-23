<?php
$title = "Data Barang | Elastomix";
require "../BackEnd/function.php";
require "../header.php";
require "../BackEnd/check_role.php";
?>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Data Master</h1>
        <!-- Button trigger modal -->
        <button type="button" style="border-radius: 10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-plus" viewBox="0 0 16 16">
                <path d="m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z" />
                <path d="M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5" />
            </svg>
            Tambah Data
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
                        <form id="dynamicForm" action="../BackEnd/function.php" method="POST">
                            <div id="formInputs">
                                <div class="form-group mb-3 d-flex">
                                    <input type="text" name="kodebarang[]" placeholder="Kode Barang" class="form-control me-2">
                                    <input type="text" name="jenisbarang[]" placeholder="Jenis Barang" class="form-control me-2" required>
                                    <input type="text" name="namabarang[]" placeholder="Nama Barang" class="form-control me-2" required>
                                    <input type="text" name="dekripsi[]" placeholder="Deskripsi barang" class="form-control me-2" required>
                                    <input type="text" name="maker[]" placeholder="Maker" class="form-control" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="addButton"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>Tambah Item</button>
                            <button type="button" class="btn btn-danger"> Hapus Item</i></button>
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

            let itemCount = 0; // Menyimpan jumlah item yang ditambahkan

            // Tambah Barang
            document.getElementById('addButton').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'form-group mb-3 d-flex';
                itemCount++; // Increment item count
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

        <a href="../export/export" style="color: white; text-decoration: none;"> <button style="border-radius: 10px;" class="btn btn-success">
                Ekspor Dokumen
            </button></a>

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

<?php include "../footer.php"; ?>