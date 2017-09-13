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
    /**
     * Test user audit, check the response for expected keys
     */
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

    /**
     * Test log email
     *
     * TODO Mock this test, use some fakemailer, add better assetions
     */
    public function testEmailLogs()
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