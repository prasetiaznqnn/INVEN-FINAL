<?php
$title = "User | Elastomix";
require "../BackEnd/function.php";
require "../BackEnd/check_role.php"; // Pastikan jalur file benar
include "../header.php";

?>
?>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">User</h1>
        <!-- Button trigger modal -->

        <!-- tabel hasil data -->
        <div class="card mb-4">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM user";
                            $result = mysqli_query($conn, $query);
                            $i = 1;
                            while ($data = mysqli_fetch_array($result)) {
                                $username = $data['username'];
                                $email = $data['email'];

                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $username; ?></td>
                                    <td><?= $email; ?></td>
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