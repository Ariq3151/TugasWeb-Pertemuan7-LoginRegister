<?php
session_start();

require_once __DIR__ . '/remember.php';

$usersFile = __DIR__ . '/users.json';

rememberRestore($usersFile);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>

<body>
    <div class="container">
        <h1>Dashboard</h1>

        <p>
            Selamat datang,
            <strong>
                <?= htmlspecialchars($_SESSION["username"]) ?>
            </strong>
        </p>

        <p>
            Email:
            <?= htmlspecialchars($_SESSION["email"]) ?>
        </p>

        <p><a href="edit-profile.php">Edit Profil</a></p>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>