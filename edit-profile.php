<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$usersFile = __DIR__ . '/users.json';
$pesanError = '';
$pesanSukses = '';

$users = json_decode(file_get_contents($usersFile), true);

if (!is_array($users)) {
    die('Data pengguna tidak dapat dibaca.');
}

$userIndex = null;

foreach ($users as $index => $user) {
    if (
        isset($user['id']) &&
        (string) $user['id'] === (string) $_SESSION['user_id']
    ) {
        $userIndex = $index;
        break;
    }
}

if ($userIndex === null) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$nama = $users[$userIndex]['nama'];
$email = $users[$userIndex]['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nama === '' || $email === '') {
        $pesanError = 'Nama dan email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesanError = 'Format email tidak valid.';
    } else {
        $emailDuplikat = false;

        foreach ($users as $index => $user) {
            if (
                $index !== $userIndex &&
                strtolower($user['email']) === strtolower($email)
            ) {
                $emailDuplikat = true;
                break;
            }
        }

        if ($emailDuplikat) {
            $pesanError = 'Email tersebut sudah digunakan akun lain.';
        } else {
            $users[$userIndex]['nama'] = $nama;
            $users[$userIndex]['email'] = $email;

            $jsonBaru = json_encode(
                $users,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );

            if (
                $jsonBaru !== false &&
                file_put_contents($usersFile, $jsonBaru, LOCK_EX) !== false
            ) {
                $_SESSION['username'] = $nama;
                $_SESSION['email'] = $email;

                $pesanSukses = 'Profil berhasil diperbarui.';
            } else {
                $pesanError = 'Profil gagal disimpan. Periksa izin file users.json.';
            }
        }
    }
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <h1>Edit Profil</h1>
        <p>Perbarui nama dan email akun kamu.</p>

        <?php if ($pesanError !== ''): ?>
            <p class="message"><?= e($pesanError) ?></p>
        <?php endif; ?>

        <?php if ($pesanSukses !== ''): ?>
            <p class="message"><?= e($pesanSukses) ?></p>
        <?php endif; ?>

        <form action="edit-profile.php" method="POST">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="<?= e($nama) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" required>

            <button type="submit">Simpan Perubahan</button>
        </form>

        <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
    </div>

</body>

</html>