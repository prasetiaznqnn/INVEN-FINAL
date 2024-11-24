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
        <h1 class="mt-4">Data Masuk</h1>

        <!-- Button modal form tambah data barang -->
        <button type="button" style="border-radius: 10px;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Masuk Barang</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-success">
                        <h1 class="modal-title fs-6 text-white" id="exampleModalLabel">Form Tambah Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- HEADER END -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="dynamicForm" action="../BackEnd/function.php" method="POST">
                            <div class="form-group mb-3 d-flex">
                                <input type="date" name="tanggal" placeholder="Tanggal Barang Masuk" class="form-control me-2" onclick="this.showPicker()" value="<?= date('Y-m-d'); ?>">

                                <input type="text" name="supplier" placeholder="Supplier" class="form-control" required>
                            </div>
                            <h2 class="fs-6">Detail:</h2>
                            <div id="formInputs">
                                <?php
                                // Mengambil data dari tabel master_barang
                                $query = "SELECT * FROM master_barang";
                                $result = mysqli_query($conn, $query);
                                $masterBarang = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                ?>
                                <div class="form-group mb-3 d-flex">
                                    <select name="kodebarang[]" class="form-control me-2" onchange="fetchBarangData(this)">
                                        <option value="">Kode Barang</option>
                                        <?php foreach ($masterBarang as $barang): ?>
                                            <option value="<?= $barang['kode_barang'] ?>"><?= $barang['kode_barang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="jenisbarang[]" class="form-control me-2 jenisbarang">
                                        <option value="">Jenis Barang</option>
                                    </select>
                                    <select name="namabarang[]" class="form-control me-2 namabarang">
                                        <option value="">Nama Barang</option>
                                    </select>
                                    <select name="maker[]" class="form-control me-2 maker">
                                        <option value="">Maker</option>
                                    </select>
                                    <input type="number" name="jumlah[]" placeholder="Jumlah barang" class="form-control me-2">
                                    <input type="text" name="note[]" placeholder="Note" class="form-control me-2">
                                </div>
                            </div>

                            <script>
                                function fetchBarangData(selectElement) {
                                    var kodeBarang = selectElement.value;

                                    // Check jika ada kode barang yang dipilih
                                    if (kodeBarang) {
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("POST", "../BackEnd/get_barang.php", true);
                                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                var response = JSON.parse(xhr.responseText);

                                                // Mendapatkan elemen parent untuk input jenisbarang, namabarang, dan maker
                                                var parent = selectElement.closest('.form-group');

                                                // Update input jenisbarang, namabarang, dan maker
                                                parent.querySelector('.jenisbarang').innerHTML = '<option value="' + response.jenis_barang + '">' + response.jenis_barang + '</option>';
                                                parent.querySelector('.namabarang').innerHTML = '<option value="' + response.nama_barang + '">' + response.nama_barang + '</option>';
                                                parent.querySelector('.maker').innerHTML = '<option value="' + response.maker + '">' + response.maker + '</option>';
                                            }
                                        };
                                        xhr.send("kode_barang=" + kodeBarang);
                                    }
                                }
                            </script>

                            <!-- Tombol tambah dan hapus -->
                            <button type="button" class="btn btn-success" id="addButton">
                                Tambah Item
                            </button>
                            <button type="button" class="btn btn-danger" id="removeButton">
                                Hapus Item
                            </button>
                            <button type="submit" class="btn btn-primary" name="addnewbarangmasuk">Submit</button>
                        </form>
                    </div>
                    <!-- Modal End -->
                </div>
            </div>
        </div>

        <script>
            document.getElementById('dynamicForm').addEventListener('submit', function(event) {
                let isValid = true;

                const inputs = document.querySelectorAll('#formInputs input, #formInputs select');

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

            <select name="kodebarang[]" class="form-control me-2" onchange="fetchBarangData(this)">
                <option value="">Kode Barang</option>
                <?php foreach ($masterBarang as $barang): ?>
                    <option value="<?= $barang['kode_barang'] ?>"><?= $barang['kode_barang'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="jenisbarang[]" class="form-control me-2 jenisbarang">
                <option value="">Jenis Barang</option>
            </select>

            <select name="namabarang[]" class="form-control me-2 namabarang">
                <option value="">Nama Barang</option>
            </select>

            <select name="maker[]" class="form-control me-2 maker">
                <option value="">Maker</option>
            </select>

            <input type="number" name="jumlah[]" placeholder="Jumlah barang" class="form-control me-2">
            <input type="text" name="note[]" placeholder="Note" class="form-control me-2">`;
                formInputs.appendChild(newInputGroup);
            });

            // Hapus Item form
            document.querySelector('.btn-danger').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const inputGroups = formInputs.getElementsByClassName('form-group mb-3');

                // Hapus grup input maksimal
                if (inputGroups.length > 1) {
                    formInputs.removeChild(inputGroups[inputGroups.length - 1]);
                } else {
                    alert("Tidak ada item untuk dihapus!");
                }
            });
        </script>

        <!-- tombol untuk export -->
        <a href="../Export/exportmasuk" style="color: white; text-decoration: none;"> <button style="border-radius: 10px;" class="btn btn-success">Ekspor Dokument</button></a>
        <!-- tombol untuk export selesai-->




        <!-- tabel hasil data -->
        <div class="card mb-4 mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>KODE BARANG</th>
                                <th>TANGGAL</th>
                                <th>SUPPLIER</th>
                                <th>JENIS BARANG</th>
                                <th>NAMA BARANG</th>
                                <th>MAKER</th>
                                <th>JUMLAH</th>
                                <th>NOTE</th>
                                <!-- <th>DETAIL</th> -->
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
                                    <td><?= $kodebarang; ?></td>
                                    <td><?= $tanggal; ?></td>
                                    <td><?= $supplier; ?></td>
                                    <td><?= $jenisbarang; ?></td>
                                    <td><?= $namabarang; ?></td>
                                    <td><?= $maker; ?></td>
                                    <td><?= $qty; ?></td>
                                    <td><?= $note; ?></td>
                                    <!-- <td><button class="btn btn-warning">Detail </button></td> -->
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
        <!-- tabel hasil data End -->
    </div>
</main>

<?php include "../footer.php"; ?>