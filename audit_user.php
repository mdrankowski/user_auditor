<?php
/**
 * Get the current user name and the directory user is in
 * Output to stdout and log file in json
 */
$path = 'logs/';
$user = trim(shell_exec('whoami'));
$directory = trim(shell_exec('pwd'));

// build json array
$userAuditJson = json_encode(
    [
        'user' => $user,
        'directory' => $directory
    ]
);

// we need logs directory let's check it first
if (!file_exists($path)) {
    mkdir($path, 0744);
}

// save the output to file first
file_put_contents($path.'script_audit.log', $userAuditJson.PHP_EOL, FILE_APPEND);

// output to stdout
header('Content-Type: application/json');
echo($userAuditJson);