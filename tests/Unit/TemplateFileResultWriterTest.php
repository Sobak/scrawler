<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider;
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

    public function testFileResultWriterGetters(): void
    {
        $resultWriter = new TemplateFileResultWriter([
            'filename' => new IncrementalFilenameProvider(),
            'template' => '{{irrelevant}}',
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);
        $resultWriter->setFilename('1');

        $this->assertEquals('{{irrelevant}}', $resultWriter->getConfiguration()['template']);
        $this->assertEquals(PostEntity::class, $resultWriter->getEntity());
        $this->assertEquals('1', $resultWriter->getFilename());
        $this->assertInstanceOf(InMemoryLogWriter::class, $resultWriter->getLogWriter());
        $this->assertInstanceOf(InMemoryOutputManager::class, $resultWriter->getOutputManager());
    }

    public function testExceptionOnNoFilenameProvider(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("For the FileResultWriter you must set the FilenameProvider under 'filename' key");

        new TemplateFileResultWriter(['template' => '{{irrelevant}}']);
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
