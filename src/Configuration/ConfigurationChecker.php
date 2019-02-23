<?php

namespace Sobak\Scrawler\Configuration;

class ConfigurationChecker
{
    public function checkConfiguration(Configuration $configuration): bool
    {
        foreach ($this->getRequiredOptions() as $requiredOption) {
            $this->checkRequiredOption($requiredOption, $configuration);
        }

        return true;
    }

    protected function getRequiredOptions(): array
    {
        return [
            'baseUrl',
        ];
    }

    protected function checkRequiredOption(string $optionName, Configuration $configuration)
    {
        $methodName = 'get' . ucfirst($optionName);

        $result = $configuration->$methodName();

        if ($result === null) {
            throw new ConfigurationException("Required configuration option '$optionName' not set");
        }
    }
}
