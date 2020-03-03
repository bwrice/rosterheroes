<?php


namespace App\Domain\Traits;


use Symfony\Component\Yaml\Yaml;

trait HasConfigAttributes
{
    public function getConfig(string $key)
    {
        $config = Yaml::parseFile(app_path() . $this->config_path);
        return array_key_exists($key, $config) ? $config[$key] : null;
    }

    public function getConfigAttribute(string $attribute)
    {
        if (! $this->$attribute) {
            $this->$attribute = $this->getConfig($attribute);
        }
        return $this->$attribute;
    }
}
