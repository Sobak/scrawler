<?php

namespace Tests\Utils;

use Sobak\Scrawler\Entity\EntityInterface;

class SimpleMatchEntity implements EntityInterface
{
    protected $match;

    public function getMatch()
    {
        return $this->match;
    }

    public function setMatch($match): void
    {
        $this->match = $match;
    }

    public function toArray(): array
    {
        return [
            'match' => $this->getMatch(),
        ];
    }
}
