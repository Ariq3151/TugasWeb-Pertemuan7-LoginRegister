<?php

session_start();

require_once __DIR__ . '/remember.php';

$usersFile = __DIR__ . '/users.json';
rememberRestore($usersFile);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $file = "users.json";

    if (file_exists($file)) {

        $data = file_get_contents($file);
        $users = json_decode($data, true);

        $foundUser = null;

        if (is_array($users)) {

            foreach ($users as $user) {

                if ($user["email"] === $email) {
                    $foundUser = $user;
                    break;
                }
            }
        }


        if (
            $foundUser &&
            password_verify($password, $foundUser["password"])
        ) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $foundUser["id"];
            $_SESSION["username"] = $foundUser["nama"];
            $_SESSION["email"] = $foundUser["email"];

            if (isset($_POST["remember"]) && $_POST["remember"] === "1") {
                rememberIssue($usersFile, $foundUser["id"]);
            } else {
                rememberRevoke($usersFile, $foundUser["id"]);
            }

            header("Location: dashboard.php");
            exit;


        } else {

            $error = "Email atau password salah!";
        }

    } else {

        $error = "Belum ada pengguna terdaftar!";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">

        <h2>Login</h2>

        <?php if ($error != ""): ?>
            <p><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>Email</label><br>
            <input type="email" name="email" required>
            <br><br>

            <label>Password</label><br>
            <input type="password" name="password" required>
            <br><br>

            <label>
                <input type="checkbox" name="remember" value="1">
                Remember Me (Ingat Saya)
            </label>
            <button type="submit">Login</button>

        </form>

        <br>

        <a href="register.php">Belum punya akun? Daftar</a>

    </div>
</body>

</html>