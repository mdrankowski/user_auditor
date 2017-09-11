<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/Auditor.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands
$application->add(new \Application\Auditor());

$application->run();