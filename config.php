<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=print', 'print', 'print');

define('DOMJUDGE_URI', 'http://192.168.13.130');
define('REQUEST_INTERVAL', 10);
define('ADMIN_IP', '127.0.0.1');

$printerName = array(
    0 => "HP_LaserJet_Professional_M1136_MFP"
);

$printCommand = "enscript -d [printer] -E[language] -b '[username] / [original]|%D %*|Page $% of $=' --mark-wrapped-lines=arrow --pages=0-10 --line-numbers --font=Courier9 [file] 2>&1";
//$printCommand="lp -d [printer] -t '[username] / [original]' -P '1-10' -o prettyprint -o Page-left=36 -o Page-right=36 -o Page-top=36 [file] 2>&1";

$langRemap = array(
    'adb' => 'ada', 'awk' => 'awk', 'bash' => 'sh', 'c' => 'c', 'csharp' => 'cpp', 'cpp' => 'cpp', 'f95' => 'f90',
    'hs' => 'haskell', 'java' => 'java', 'js' => 'javascript', 'kt' => 'kt', 'lua' => 'lua', 'pas' => 'pascal',
    'pl' => 'perl', 'sh' => 'sh', 'plg' => 'prolog', 'py' => 'python', 'py2' => 'python', 'py3' => 'python',
    'r' => 'r', 'rb' => 'ruby', 'scala' => 'scala', 'swift' => 'swift', 'plain' => null
);

//error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
session_start();

function response($code, $msg = "", $data = null)
{
    $result = array('code' => $code, 'msg' => $msg, 'data' => $data);
    echo json_encode($result);
    exit();
}