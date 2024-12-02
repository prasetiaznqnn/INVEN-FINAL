if(isset($_POST['adduser'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $departemen = mysqli_real_escape_string($conn, $_POST['$departemen']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role_id = 2;

    $check_query = "SELECT * FROM user WHERE username = '$username";
    $checkResult = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Username sudah digunakan! Silahkan gunakan username yang lain.'); window.history.back();<script>";
        exit();
    }

    $hashed_password = md5($password);

    $query = "INSERT INTO user (username, departemen, password, role_id) VALUES ('$username', '$departemen', '$password', '$role_id')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('User berhasil ditambahkan!'); window.location.href='user.php';</script>";
        exit();
    } else {
        echo "<script>alert('Terjadi Kesalahan: " . mysqli_error($conn) . " ');</script>";
        exit();
    }
}

