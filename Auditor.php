<?php
/**
 * Get the current user name and the directory user is in
 * Output to stdout and log file in json
 */
namespace Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class Auditor extends Command {

    // path to logs
    private $path;

    /**
     * AuditUser constructor
     */
    public function __construct()
    {
        $this->path = __DIR__.'/logs/';

        return parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this
            ->setName('auditor')
            ->setDescription('Run user auditor')
            ->addArgument('function', InputArgument::REQUIRED, 'Function name (available commands: "audit_user", "email_logs")')
            ->addArgument('mailTo', InputArgument::OPTIONAL, 'Email address for email_logs function')
            ->addArgument('mailFrom', InputArgument::OPTIONAL, 'Email address the email is sent from');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $function = $input->getArgument('function');

        return call_user_func([$this, $function], $input, $output);
    }

    /**
     * Audit user
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function audit_user(InputInterface $input, OutputInterface $output)
    {
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
        if (!file_exists($this->path)) {
            mkdir($this->path, 0744);
        }

        // save the output to file first
        file_put_contents($this->path.'script_audit.log', $userAuditJson.PHP_EOL, FILE_APPEND);

        $output->write($userAuditJson);
    }

    /**
     * Email logs
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    public function email_logs(InputInterface $input, OutputInterface $output)
    {
        $currentDirectory = trim(shell_exec('pwd'));
        $currentTime = date('Y-m-d H:i:s');

        // no logs directory? We can't do much then
        if (!file_exists($this->path)) {
            $output->write($currentTime.': logs directory is missing'.PHP_EOL);
            return false;
        }

        // check we have an email param
        $mailTo = $input->getArgument('mailTo');
        if (empty($mailTo)) {
            $output->write($currentTime.': mailTo param is required'.PHP_EOL);
            return false;
        }

        // validate to email address
        if (!filter_var($mailTo, FILTER_VALIDATE_EMAIL)) {
            $output->write($currentTime.': email address '.$mailTo.' is invalid'.PHP_EOL);
            return false;
        }

        $mailFrom = $input->getArgument('mailFrom') ?? 'test@test.com';
        // validate from email address
        if (!filter_var($mailTo, FILTER_VALIDATE_EMAIL)) {
            $output->write($currentTime.': email address '.$mailFrom.' is invalid'.PHP_EOL);
            return false;
        }

        // get the list of files
        $fileList = array_filter(scandir($this->path), function($fileName) {
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
        if (!mail($mailTo, 'List of logs files', $message, [], '-f'.$mailFrom)) {
            $output->write($currentTime.': there was a problem sending an email'.PHP_EOL);
            return false;
        }
        // all good
        $output->write($currentTime.': email sent successfully, '.count($fileList).' files included'.PHP_EOL);
        return true;
    }
}