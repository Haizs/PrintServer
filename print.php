<?php

require_once('config.php');

function printFile(
    string $printer,
    string $filename,
    string $origname,
    string $language,
    string $username
): array
{
    global $printCommand;
    $replaces = [
        '[file]' => escapeshellarg($filename),
        '[printer]' => escapeshellarg($printer),
        '[language]' => escapeshellcmd($language),
        '[username]' => escapeshellcmd($username),
        '[original]' => escapeshellcmd($origname)
    ];
    $cmd = str_replace(array_keys($replaces), array_values($replaces), $printCommand);
    exec($cmd, $output, $retval);
    return [$retval == 0, implode("\n", $output)];
}
