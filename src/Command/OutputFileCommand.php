<?php

namespace App\Command;

use App\Service\DownloaderInterface;
use App\Service\EmailUtilInterface;
use App\Service\File\OutputMethodManagerInterface;
use App\Service\Rule\ValidatorManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:output-file',
    description: 'Download the json file online and then output a new file with specific format.',
    aliases: ['app:create-file'],
    hidden: false
)]
class OutputFileCommand extends Command
{
    const DEFAULT_URL = "https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl";
    /** @var OutputMethodManagerInterface $outputMethodManager */
    private OutputMethodManagerInterface $outputMethodManager;

    /** @var DownloaderInterface $downloader */
    private DownloaderInterface $downloader;

    private ValidatorManagerInterface $validatorManager;

    private EmailUtilInterface $emailUtil;

    public function __construct(DownloaderInterface $downloader, OutputMethodManagerInterface $outputMethodManager, ValidatorManagerInterface $validatorManager, EmailUtilInterface $emailUtil)
    {
        $this->outputMethodManager = $outputMethodManager;
        $this->downloader = $downloader;
        $this->validatorManager = $validatorManager;
        $this->emailUtil = $emailUtil;
        parent::__construct();
    }

    /**
     * Execute the command for download the data and output a file according to its required format
     * Eg, php bin/console  app:output-file csv
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $splitFileThresholdLimit = $input->getArgument('row_limit_to_split_the_file');
        $recipient = $input->getArgument('recipient_email');
        $output->writeln('Output file: ' . $input->getArgument('file_type'));
        $output->writeln('Row limit to split the one big file into multiple file: ' . $splitFileThresholdLimit);
        $output->writeln('Email address for recipient: ' . $recipient);

        try {
            isset($splitFileThresholdLimit)
                ?
                $this->downloader->download($this->validatorManager, $this->outputMethodManager, $this->emailUtil, $input->getArgument('file_type'), self::DEFAULT_URL, $recipient, $input->getArgument('row_limit_to_split_the_file'))
                :
                $this->downloader->download($this->validatorManager, $this->outputMethodManager, $this->emailUtil, $input->getArgument('file_type'), self::DEFAULT_URL, $recipient);
        } catch (\Exception $e) {
            var_dump($e);//Should log and send out an email about this exception
            return Command::INVALID;
        }
        return Command::SUCCESS;
    }


    /**
     * configure an argument
     */
    protected function configure(): void
    {
        $this->setHelp('Download the json file online and then output a new file with specific format.');
        $this->addArgument('file_type', InputArgument::REQUIRED, 'The file output type.');
        $this->addArgument('recipient_email', InputArgument::OPTIONAL, 'Email address for recipient.');
        $this->addArgument('row_limit_to_split_the_file', InputArgument::OPTIONAL, 'Row limit to split the file.');
    }
}