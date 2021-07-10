<?php

namespace Backend\Validator;

abstract class AbstractValidator
{
    /**
     * @var array
     */
    protected static $errorMessages = [];

    /**
     * Returns true if $value meets the validation requirements.
     *
     * @param mixed $value
     * @return bool
     */
    abstract public static function isValid($value): bool;

    /**
     * Returns an array of messages that explain why the isValid() returned false.
     *
     * @return array
     */
    public static function getMessages(): array
    {
        return self::$errorMessages;
    }

    /**
     * Add an error to the error messages array.
     *
     * @param string $value
     */
    protected static function error(string $value): void
    {
        self::$errorMessages[] = $value;
    }
}