<?php

namespace App\Service;

use App\Service\File\OutputMethodManagerInterface;
use App\Service\Rule\ValidatorManagerInterface;
use SplFileObject;

class Downloader implements DownloaderInterface
{
    /**
     * Array for store the validator's name & defining the validator execution order
     *
     * @var array|string[]
     */
    private array $validatorNames = ['format', 'totalOrderValue'];

    private array $generatedFiles = [];

    /**
     * Integration function for downloading json data from URL,
     * data validation and output the data according to the given format
     *
     * @param ValidatorManagerInterface $validatorManager
     * @param OutputMethodManagerInterface $outputMethodManager
     * @param EmailUtilInterface $emailUtil
     * @param string $outputFormat
     * @param string $url
     * @param string $recipient
     * @param int $splitFileThresholdLimit
     */
    public function download(ValidatorManagerInterface $validatorManager, OutputMethodManagerInterface $outputMethodManager, EmailUtilInterface $emailUtil, string $outputFormat, string $url, string $recipient, int $splitFileThresholdLimit = 10000): void
    {
        $fileReadHandler = new SplFileObject($url);
        while (!$fileReadHandler->eof()) {
            $outputFile = 'file/' . uniqid() . '.' . $outputFormat;
            $fileWriteHandler = $outputMethodManager->createHandler($outputFormat, $outputFile);
            $this->generatedFiles[] = $outputFile;
            $rowCount = 0;
            $outputMethodManager->restore($outputFormat);
            while (!$fileReadHandler->eof()) {
                $line = $fileReadHandler->fgets();
                $isValid = true;
                foreach ($this->validatorNames as $name) {
                    $isValid = $validatorManager->validate($line, $name);
                    if (!$isValid) {
                        break;
                    }
                }
                if ($isValid) {
                    $outputMethodManager->output(json_decode($line, true), $outputFormat, $fileWriteHandler);
                }
                $rowCount++;
                if ($rowCount >= $splitFileThresholdLimit)
                    break;
            }
            $outputMethodManager->finalize($outputFormat, $fileWriteHandler, $outputFile);
            // close the $fileWriteHandler
            $fileWriteHandler = null;
        }
        $emailUtil->sendEmail($recipient, $this->generatedFiles);
        // close the SplFileObject $fileReadHandler
        $fileReadHandler = null;

    }
}