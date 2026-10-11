
<?php

function rememberCookieOptions($expires)
{
    return [
        'expires' => $expires,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function rememberIssue($usersFile, $userId)
{
    $users = json_decode(file_get_contents($usersFile), true);

    if (!is_array($users)) {
        return false;
    }

    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $expires = time() + (30 * 24 * 60 * 60);

    foreach ($users as &$user) {
        if ((string) $user['id'] === (string) $userId) {
            $user['remember_selector'] = $selector;
            $user['remember_token_hash'] = hash('sha256', $validator);
            $user['remember_expires'] = $expires;
            break;
        }
    }
    unset($user);

    if (file_put_contents(
        $usersFile,
        json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    ) === false) {
        return false;
    }

    setcookie(
        'remember_me',
        $selector . ':' . $validator,
        rememberCookieOptions($expires)
    );

    return true;
}

function rememberRestore($usersFile)
{
    if (isset($_SESSION['user_id']) || empty($_COOKIE['remember_me'])) {
        return;
    }

    $parts = explode(':', $_COOKIE['remember_me'], 2);

    if (
        count($parts) !== 2 ||
        !ctype_xdigit($parts[0]) ||
        !ctype_xdigit($parts[1]) ||
        strlen($parts[0]) !== 32 ||
        strlen($parts[1]) !== 64
    ) {
        rememberForgetCookie();
        return;
    }

    [$selector, $validator] = $parts;

    $users = json_decode(file_get_contents($usersFile), true);

    if (!is_array($users)) {
        rememberForgetCookie();
        return;
    }

    foreach ($users as $user) {
        if (
            isset(
                $user['remember_selector'],
                $user['remember_token_hash'],
                $user['remember_expires']
            ) &&
            hash_equals($user['remember_selector'], $selector)
        ) {
            $valid = (int) $user['remember_expires'] > time()
                && hash_equals(
                    $user['remember_token_hash'],
                    hash('sha256', $validator)
                );

            if ($valid) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nama'];
                $_SESSION['email'] = $user['email'];

                rememberIssue($usersFile, $user['id']);
                return;
            }

            break;
        }
    }

    rememberForgetCookie();
}

function rememberForgetCookie()
{
    setcookie(
        'remember_me',
        '',
        rememberCookieOptions(time() - 3600)
    );
}

function rememberRevoke($usersFile, $userId)
{
    $users = json_decode(file_get_contents($usersFile), true);

    if (!is_array($users)) {
        rememberForgetCookie();
        return;
    }

    foreach ($users as &$user) {
        if ((string) $user['id'] === (string) $userId) {
            unset(
                $user['remember_selector'],
                $user['remember_token_hash'],
                $user['remember_expires']
            );
        }
    }
    unset($user);

    file_put_contents(
        $usersFile,
        json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );

    rememberForgetCookie();
}
