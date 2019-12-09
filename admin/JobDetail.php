<?php

require_once('../config.php');

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    response(1, "No job selected.");
    die();
}

$id = (int)$_GET["id"];
$sth = $pdo->prepare('SELECT file FROM job WHERE id=?');
if ($sth->execute(array($id))) {
    $result = $sth->fetch(PDO::FETCH_ASSOC)['file'];
    $data = @file_get_contents($result);
    response(0, "Success", $data);
} else {
    response(-1, "Database Error.");
}