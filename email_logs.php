<?php
/**
 * Send the list of files from logs directory to the given user
 */
$path = 'logs/';
$currentDirectory = trim(shell_exec('pwd'));
$currentTime = date('Y-m-d H:i:s');

ini_set('sendmail_path', '/usr/sbin/sendmail');

// no logs directory? We can't do much then
if (!file_exists($path)) {
    echo $currentTime.': logs directory is missing'.PHP_EOL;
    exit(1);
}

// check we have an email param
$mailTo = $argv[1] ?? $_GET['mailTo'];
if (empty($mailTo)) {
    echo $currentTime.': mailTo param is required'.PHP_EOL;
    exit(1);
}

// validate email address
if (!filter_var($mailTo, FILTER_VALIDATE_EMAIL)) {
    echo $currentTime.': email address '.$mailTo.' is invalid'.PHP_EOL;
    exit(1);
}

// get the list of files
$fileList = array_filter(scandir($path), function($fileName) {
    if (in_array($fileName, ['.', '..'])) {
        return false;
    }
    return true;
});

// build simple email message
$message = 'Current list of files from directory '.$currentDirectory.'/'.$path.' below:'.PHP_EOL;
foreach ($fileList as $fileName) {
    $message .= $fileName.PHP_EOL;
}

// send the list of files to the user
if (!mail($mailTo, 'List of logs files', $message)) {
    echo $currentTime.': there was a problem sending an email'.PHP_EOL;
    exit(1);
}

// all good
echo $currentTime.': email sent successfully, '.count($fileList).' files included'.PHP_EOL;
exit();


