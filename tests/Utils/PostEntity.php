<?php

declare(strict_types=1);

namespace Tests\Utils;

use Sobak\Scrawler\Entity\EntityInterface;

class PostEntity implements EntityInterface
{
    protected $content;

    protected $title;

    public function getContent()
    {
        return $this->content;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->getContent(),
            'title' => $this->getTitle(),
        ];
    }
}
