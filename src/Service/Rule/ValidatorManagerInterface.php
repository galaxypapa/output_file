<?php

namespace App\Service\Rule;

interface ValidatorManagerInterface
{
    /**
     * Add validator instance into an array named as $validators
     *
     * @param ValidatorInterface $validator
     */
    function addValidators(ValidatorInterface $validator): void;

    /**
     * Call a detail validator instance's validate method
     *
     * @param string $data
     * @param string $name
     * @return bool
     */
    function validate(string $data, string $name): bool;
}