<?php
/**
 * vendor/phpunit/phpunit/phpunit AuditorTest.php
 */
namespace Application;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/Auditor.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use PHPUnit\Framework\TestCase;

class AuditorTest extends TestCase
{
    public function testUserAudit()
    {
        $application = new Application();
        $application->add(new Auditor());

        $command = $application->find('auditor');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'function' => 'audit_user']);
        $result = json_decode($commandTester->getDisplay(), true);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('directory', $result);
    }

    public function testEmailAudit()
    {
        $application = new Application();
        $application->add(new Auditor());

        $command = $application->find('auditor');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'function' => 'email_logs', 'mailTo' => 'email@email.com']);
        $result = $commandTester->getDisplay();

        $this->assertRegExp('/email sent successfully/', $result);
    }
}