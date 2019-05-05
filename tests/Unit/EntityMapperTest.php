<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Entity\EntityMapper;
use Tests\Utils\PostEntity;

class EntityMapperTest extends TestCase
{
    public function testMappingArrayToEntity(): void
    {
        $result = [
            'title' => 'Post title',
            'content' => 'Post content',
        ];

        /** @var PostEntity $post */
        $post = EntityMapper::resultToEntity($result, PostEntity::class);

        $this->assertInstanceOf(PostEntity::class, $post);
        $this->assertEquals('Post title', $post->getTitle());
        $this->assertEquals('Post content', $post->getContent());
    }

    public function testMappingEntityToArray(): void
    {
        $post = new PostEntity();
        $post->setTitle('Post title');
        $post->setContent('Post content');

        $array = EntityMapper::entityToArray($post);

        $this->assertIsArray($array);
        $this->assertCount(2, $array);
        $this->assertEquals('Post title', $array['title']);
        $this->assertEquals('Post content', $array['content']);
    }
}
