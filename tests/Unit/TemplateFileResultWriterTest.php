<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\LiteralFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\TemplateFileResultWriter;
use Tests\Utils\InMemoryLogWriter;
use Tests\Utils\InMemoryOutputManager;
use Tests\Utils\PostEntity;

class TemplateFileResultWriterTest extends TestCase
{
    public function testTemplateFileResultWriterWithExtension(): void
    {
        $resultWriter = new TemplateFileResultWriter([
            'filename' => new LiteralFilenameProvider(['filename' => 'test']),
            'extension' => 'txt',
            'template' => "Title: {{title}} Content: {{content}}\nUnknown: {{unknown}}"
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);

        $this->writeResult($resultWriter);

        $this->assertEquals(
            "Title: Post title Content: Post content\nUnknown: {{unknown}}",
            InMemoryOutputManager::$filesystem['test']['test.txt']
        );
    }

    public function testTemplateFileResultWriterWithoutExtension(): void
    {
        $resultWriter = new TemplateFileResultWriter([
            'filename' => new LiteralFilenameProvider(['filename' => 'test']),
            'template' => "Unknown: {{unknown}}"
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);

        $this->writeResult($resultWriter);

        $this->assertEquals("Unknown: {{unknown}}", InMemoryOutputManager::$filesystem['test']['test']);
    }

    protected function writeResult(TemplateFileResultWriter $resultWriter): void
    {
        $post = new PostEntity();
        $post->setTitle('Post title');
        $post->setContent('Post content');

        $resultWriter->setFilename($resultWriter->getFilenameProvider()->generateFilename($post));
        $resultWriter->write($post);
    }
}
