<?php

namespace App\Service\Rule;

class FormatValidator implements ValidatorInterface
{
    const NAME = 'format';

    /**
     * Validate data format
     *
     * @param string $data
     * @return bool
     */
    public function validate(string $data): bool
    {
        json_decode($data);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}