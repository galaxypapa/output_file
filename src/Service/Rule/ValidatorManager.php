<?php

namespace App\Service\Rule;


class ValidatorManager implements ValidatorManagerInterface
{
    private array $validators;

    /**
     * Add validator instance into an array named as $validators
     *
     * @param ValidatorInterface $validator
     */
    public function addValidators(ValidatorInterface $validator): void
    {
        $this->validators[$validator::NAME] = $validator;
    }

    /**
     * Call a detail validator instance's validate method
     *
     * @param string $data
     * @param string $name
     * @return bool
     */
    public function validate(string $data, string $name): bool
    {
        return $this->validators[$name]->validate($data);
    }
}