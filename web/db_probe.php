<?php

$remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$allowed = $remoteAddr === '127.0.0.1'
    || $remoteAddr === '::1'
    || preg_match('/^10\./', $remoteAddr)
    || preg_match('/^192\.168\./', $remoteAddr)
    || preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $remoteAddr);

if (!$allowed) {
    header('Content-Type: text/plain; charset=utf-8', true, 403);
    echo "forbidden\n";
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

$config = require dirname(__FILE__) . '/../Application/Common/Conf/product.php';
$host = isset($config['DB_HOST']) ? $config['DB_HOST'] : '127.0.0.1';
if (isset($_GET['host']) && $_GET['host'] !== '') {
    $host = (string) $_GET['host'];
}
$db = isset($config['DB_NAME']) ? $config['DB_NAME'] : '';
$user = isset($config['DB_USER']) ? $config['DB_USER'] : '';
$pass = isset($config['DB_PWD']) ? $config['DB_PWD'] : '';
$port = isset($config['DB_PORT']) ? (int) $config['DB_PORT'] : 3306;

echo 'PHP_SAPI=' . PHP_SAPI . "\n";
echo 'HOSTNAME=' . gethostname() . "\n";
echo "REMOTE_ADDR={$remoteAddr}\n";
echo "DB_HOST={$host}\n";
echo 'RESOLVED_IPV4=' . gethostbyname($host) . "\n";
echo "DB_NAME={$db}\n";
echo "DB_USER={$user}\n";
echo "DB_PORT={$port}\n";

$start = microtime(true);
$errno = 0;
$errstr = '';
$socket = @fsockopen($host, $port, $errno, $errstr, 5);
$socketMs = (int) round((microtime(true) - $start) * 1000);
echo 'fsockopen=' . ($socket ? 'ok' : 'fail') . " time_ms={$socketMs}";
if (!$socket) {
    echo " errno={$errno} errstr={$errstr}";
}
echo "\n";
if ($socket) {
    fclose($socket);
}

$mysqli = @mysqli_init();
if ($mysqli) {
    mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    $start = microtime(true);
    $mysqliConnected = @mysqli_real_connect($mysqli, $host, $user, $pass, $db, $port);
    $mysqliMs = (int) round((microtime(true) - $start) * 1000);
    echo 'mysqli=' . ($mysqliConnected ? 'ok' : 'fail') . " time_ms={$mysqliMs}";
    if (!$mysqliConnected) {
        echo ' errno=' . mysqli_connect_errno() . ' errstr=' . mysqli_connect_error();
    }
    echo "\n";
    if ($mysqliConnected) {
        $result = @mysqli_query($mysqli, 'SELECT COUNT(*) AS c FROM member');
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo 'mysqli_query=ok count=' . (isset($row['c']) ? $row['c'] : '') . "\n";
        } else {
            echo 'mysqli_query=fail errstr=' . mysqli_error($mysqli) . "\n";
        }
        mysqli_close($mysqli);
    }
} else {
    echo "mysqli=init_fail\n";
}

if (class_exists('PDO')) {
    $start = microtime(true);
    try {
        $pdo = new PDO(
            'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $db,
            $user,
            $pass,
            array(
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        );
        $pdoMs = (int) round((microtime(true) - $start) * 1000);
        echo "pdo=ok time_ms={$pdoMs}\n";
        $stmt = $pdo->query('SELECT COUNT(*) AS c FROM member');
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : array();
        echo 'pdo_query=ok count=' . (isset($row['c']) ? $row['c'] : '') . "\n";
    } catch (Throwable $e) {
        $pdoMs = (int) round((microtime(true) - $start) * 1000);
        echo 'pdo=fail time_ms=' . $pdoMs . ' class=' . get_class($e) . ' msg=' . $e->getMessage() . "\n";
    }
}