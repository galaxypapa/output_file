<?php

namespace App\Service\File;

interface OutputMethodManagerInterface
{
    /**
     * Add the output format instances into the array variable named as $outputMethods in OutputMethodManager
     *
     * @param OutputMethodInterface $outputMethod
     */
    function addOutputMethods(OutputMethodInterface $outputMethod): void;

    /**
     * Call a detail output method instance's output method
     *
     * @param array $data
     * @param string $type
     * @param $fileHandler
     */
    function output(array $data, string $type, $fileHandler): void;

    /**
     * Restore the output instance default setting
     *
     * @param string $type
     */
    function restore(string $type): void;

    /**
     * Call output file class to create a file handler
     *
     * @param string $type
     * @param string $outputFile
     * @return mixed
     */
    function createHandler(string $type, string $outputFile): mixed;

    /**
     * Call the output instance's finalize hook method
     *
     * @param string $type
     * @param $fileHandler
     * @param $outputFile
     */
    function finalize(string $type, $fileHandler, $outputFile): void;
}