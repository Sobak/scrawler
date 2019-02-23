<?php

namespace Sobak\Scrawler\Configuration;

class ConfigurationChecker
{
    public function checkConfiguration(Configuration $configuration): bool
    {
        foreach ($this->getRequiredOptions() as $requiredOption) {
            $this->checkRequiredOption($requiredOption, $configuration);
        }

        foreach ($this->getArrayOptions() as $arrayOption) {
            $this->checkArrayOption($arrayOption, $configuration);
        }

        return true;
    }

    protected function getArrayOptions(): array
    {
        return [
            'logWriters',
        ];
    }

    protected function getRequiredOptions(): array
    {
        return [
            'baseUrl',
        ];
    }

    protected function checkArrayOption(string $optionName, Configuration $configuration): bool
    {
        if (method_exists($this, $validatorMethod = 'validate' . ucfirst($optionName))) {
            $methodName = 'get' . ucfirst($optionName);

            return $this->$validatorMethod($configuration->$methodName(), $configuration);
        }

        return true;
    }

    protected function checkRequiredOption(string $optionName, Configuration $configuration): bool
    {
        $methodName = 'get' . ucfirst($optionName);

        if (method_exists($this, $validatorMethod = 'validate' . ucfirst($optionName))) {
            return $this->$validatorMethod($configuration->$methodName(), $configuration);
        }

        $result = $configuration->$methodName();

        if ($result === null) {
            throw new ConfigurationException("Required configuration option '$optionName' not set");
        }

        return true;
    }
}
