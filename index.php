<?php

require_once('config.php');
require_once('print.php');

header('Access-Control-Allow-Origin: ' . DOMJUDGE_URI);
header('Access-Control-Allow-Methods: POST, GET');
header('Content-type: application/json');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: ./index.html");
    exit();
}

if (!isset($_FILES['print']) || !isset($_POST['print'])) {
    response(-1, "POST incompletely.");
}

if ($_FILES['print']['error']['code'] !== UPLOAD_ERR_OK) {
    $phpFileUploadErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );
    response($_FILES['print']['error']['code'], $phpFileUploadErrors[$_FILES['print']['error']['code']]);
}
if (isset($_SESSION['last_submit_time'])) {
    $interval = time() - $_SESSION['last_submit_time'];
    if ($interval < REQUEST_INTERVAL) {
        response(10, "Sending too frequently!\nPlease wait after " . (REQUEST_INTERVAL - $interval) . " seconds.");
    }
}

$ip = $_SERVER["REMOTE_ADDR"];
$username = 'IP: ' . $ip;
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    $sth = $pdo->prepare('SELECT username FROM user WHERE ip_address=?');
    if ($sth->execute(array($ip))) {
        $result = $sth->fetch(PDO::FETCH_ASSOC)['username'];
        if (!empty($result)) $username = $_SESSION['username'] = $result;
    }
} else {
    $username = $_SESSION['username'];
}

$uploadFile = getcwd() . '/upload/' . $username . '.' . $_FILES['print']['name']['code'] . '.' . time() . '.txt';
if (!move_uploaded_file($_FILES['print']['tmp_name']['code'], $uploadFile)) {
    response(11, "Saving file failed.\nPlease send again.");
}

$language = isset($langRemap[$_POST['print']['langid']]) ? $langRemap[$_POST['print']['langid']] : '';
$printer = $printerName[rand(0, count($printerName) - 1)];
$sth = $pdo->prepare('INSERT INTO job (username, ip_address, file, origname, language, status) VALUES (?, ?, ?, ?, ?, ?)');
$sth->execute([$username, $ip, $uploadFile, $_FILES['print']['name']['code'], $language, 0]);

$ret = printFile($printer, $uploadFile, $_FILES['print']['name']['code'], $language, $username);
$_SESSION['last_submit_time'] = time();
if ($ret[0]) {
    response(0, str_replace($printer, "Printer", $ret[1]));
} else {
    response(0, "Sending to printer.");
}
