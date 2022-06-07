<?php

namespace App\Service\File;

class OutputMethodManager implements OutputMethodManagerInterface
{
    private array $outputMethods;

    /**
     * @param OutputMethodInterface $outputMethod
     */
    public function addOutputMethods(OutputMethodInterface $outputMethod): void
    {
        $this->outputMethods[$outputMethod::TYPE] = $outputMethod;
    }

    /**
     * @param array $data
     * @param string $type
     * @param $fileHandler
     */
    public function output(array $data, string $type, $fileHandler): void
    {
        $this->checkSupport($type);
        $this->outputMethods[$type]->output($data, $fileHandler);
    }

    /**
     * @param string $type
     */
    public function checkSupport(string $type): void
    {
        if (!array_key_exists($type, $this->outputMethods))
            throw new \LogicException(sprintf('Cannot output as %s format.', $type));
    }

    /**
     * Restore the output instance default setting
     *
     * @param string $type
     */
    public function restore(string $type): void
    {
        $this->outputMethods[$type]->restore();
    }

    /**
     * Call output file class to create a file handler
     *
     * @param string $type
     * @param string $outputFile
     * @return mixed
     */
    public function createHandler(string $type, string $outputFile): mixed
    {
        return $this->outputMethods[$type]->createHandler($outputFile);
    }

    /**
     * Call the output instance's finalize hook method
     *
     * @param string $type
     * @param $fileHandler
     * @param $outputFile
     */
    public function finalize(string $type, $fileHandler, $outputFile): void
    {
        $this->outputMethods[$type]->finalize($fileHandler, $outputFile);
    }
}