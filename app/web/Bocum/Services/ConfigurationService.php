<?php

namespace Bocum\Services;

use Bocum\Models\Configuration;

class ConfigurationService
{
    /**
     * Cache for all configurations
     * @var array|null
     */
    private $configurations = null;

    /**
     * Load all configurations from database (only once)
     */
    private function loadConfigurations(): void
    {
        if ($this->configurations === null) {
            $this->configurations = [];
            
            $configs = Configuration::all();
            foreach ($configs as $config) {
                $this->configurations[$config->key] = $config->value;
            }
        }
    }

    /**
     * Get configuration value by key
     * 
     * @param string $key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public function getConfiguration(string $key, $default = null)
    {
        // Load configurations on first call
        $this->loadConfigurations();

        // Return the configuration value or default
        return $this->configurations[$key] ?? $default;
    }

    /**
     * Get all configurations
     * 
     * @return array
     */
    public function getAllConfigurations(): array
    {
        $this->loadConfigurations();
        return $this->configurations;
    }

    /**
     * Check if a configuration key exists
     * 
     * @param string $key
     * @return bool
     */
    public function hasConfiguration(string $key): bool
    {
        $this->loadConfigurations();
        return isset($this->configurations[$key]);
    }

    /**
     * Refresh the configuration cache
     * Useful if configurations are updated during the request
     */
    public function refresh(): void
    {
        $this->configurations = null;
        $this->loadConfigurations();
    }
}
