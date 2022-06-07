<?php

namespace App\Service;

use App\Service\File\OutputMethodManagerInterface;
use App\Service\Rule\ValidatorManagerInterface;

interface DownloaderInterface
{

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
    function download(ValidatorManagerInterface $validatorManager, OutputMethodManagerInterface $outputMethodManager, EmailUtilInterface $emailUtil, string $outputFormat, string $url, string $recipient, int $splitFileThresholdLimit): void;
}