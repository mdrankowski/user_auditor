<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/Auditor.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Application\Auditor;

$application = new Application();
$application->add(new Auditor());

$command = $application->find('auditor');
$commandTester = new CommandTester($command);
$commandTester->execute(['command' => $command->getName(), 'function' => 'audit_user']);
echo $commandTester->getDisplay();