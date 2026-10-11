<?php
session_start();

require_once __DIR__ . '/remember.php';

$usersFile = __DIR__ . '/users.json';

if (isset($_SESSION['user_id'])) {
    rememberRevoke($usersFile, $_SESSION['user_id']);
} else {
    rememberForgetCookie();
}

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;