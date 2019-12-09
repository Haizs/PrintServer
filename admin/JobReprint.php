<?php

require_once('../config.php');
require_once('../print.php');

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    response(1, "No job selected.");
    die();
}

$id = (int)$_GET["id"];
$sth = $pdo->prepare('SELECT * FROM job WHERE id=?');
if ($sth->execute(array($id))) {
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $printer = $printerName[rand(0, count($printerName) - 1)];
    $ret = printFile($printer, $result['file'], $result['origname'], $result['language'], $result['username']);
    if ($ret[0]) {
        response(0, $ret[1]);
    } else {
        response(1, "Print command failed.");
    }
} else {
    response(-1, "Database Error.");
}