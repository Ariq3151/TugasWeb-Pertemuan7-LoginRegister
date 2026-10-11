<?php
$error = "";
$sukses = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($nama) || empty($email) || empty($password)) {
        $error = "Semua data wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {

        $nama = htmlspecialchars($nama);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $file = "users.json";

        if (file_exists($file)) {
            $data = file_get_contents($file);
            $users = json_decode($data, true);

            if (!is_array($users)) {
                $users = [];
            }
        } else {
            $users = [];
        }

        $emailAda = false;

        foreach ($users as $user) {
            if ($user["email"] === $email) {
                $emailAda = true;
                break;
            }
        }

        if ($emailAda) {
            $error = "Email sudah terdaftar!";
        } else {

            $newUser = [
                "id" => count($users) + 1,
                "nama" => $nama,
                "email" => $email,
                "password" => $hashedPassword
            ];

            $users[] = $newUser;

            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT)
            );

            $sukses = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>

<body>
    <div class="container">

        <h2>Registrasi</h2>

        <?php if ($error != ""): ?>
            <p><?= $error ?></p>
        <?php endif; ?>

        <?php if ($sukses != ""): ?>
            <p><?= $sukses ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>Nama</label><br>
            <input type="text" name="nama" required>
            <br><br>

            <label>Email</label><br>
            <input type="email" name="email" required>
            <br><br>

            <label>Password</label><br>
            <input type="password" name="password" required>
            <br><br>

            <button type="submit">Daftar</button>

        </form>

        <br>

        <a href="login.php">Sudah punya akun? Login</a>

    </div>
</body>

</html>