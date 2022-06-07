<?php

namespace App\Service\Rule;

interface ValidatorInterface
{
    /**
     * Validate the data according to different rule
     *
     * @param string $data
     * @return bool
     */
    function validate(string $data): bool;
}