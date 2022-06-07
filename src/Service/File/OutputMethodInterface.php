<?php

namespace App\Service\File;

interface OutputMethodInterface
{
    /**
     * Output the data into a file according to the required format
     *
     * @param array $data
     * @param $fileHandler
     */
    function output(array $data, $fileHandler): void;

    /**
     * Restore the output instance default setting
     *
     */
    function restore(): void;

    /**
     * @param string $outputFile
     * @return mixed
     */
    function createHandler(string $outputFile): mixed;

    /**
     * Call the output instance's finalize hook method
     *
     * @param $fileHandler
     * @param string $outputFile
     */
    function finalize($fileHandler, string $outputFile): void;
}