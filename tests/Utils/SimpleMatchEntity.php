<?php

declare(strict_types=1);

namespace Tests\Utils;

class SimpleMatchEntity
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
}
