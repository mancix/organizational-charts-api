<?php

namespace Backend\Utils;

use Backend\Exception\BadConfigurationException;

class Configuration
{
    /**
     * Load the configuration.
     *
     * @return array
     */
    public static function getConfig(): array
    {
        return include __DIR__ . '/../../config.php';
    }

    /**
     * Get the database configuration.
     *
     * @return array
     * @throws BadConfigurationException
     */
    public static function getDatabaseConfig(): array
    {
        $config = self::getConfig();
        if (!isset($config['db'])) {
            throw new BadConfigurationException("Missing database configuration");
        }

        return $config['db'];
    }
}