<?php

require_once('../config.php');

$startID = 1;
if (isset($_GET["start"]) && ctype_digit($_GET["start"])) $startID = (int)$_GET["start"];
$sth = $pdo->prepare('SELECT * FROM job WHERE id>=? ORDER BY id DESC');
if ($sth->execute(array($startID))) {
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    response(0, "OK", $result);
} else {
    response(-1, "Database Error.");
}