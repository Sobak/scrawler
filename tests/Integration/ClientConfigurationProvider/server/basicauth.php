<?php

if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) === false) {
    header('WWW-Authenticate: Basic realm="Test"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'FAILED';
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Basic auth test</title>
</head>
<body>

<p class="result">
    <?= ($_SERVER['PHP_AUTH_USER'] === 'test' && $_SERVER['PHP_AUTH_PW'] === 'password') ? 'OK' : 'FAILED'; ?>
</p>

</body>
</html>
