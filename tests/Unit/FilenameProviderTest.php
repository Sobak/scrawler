<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\EntityPropertyFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\RandomFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\FileResultWriter;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Tests\Utils\InMemoryLogWriter;
use Tests\Utils\InMemoryOutputManager;
use Tests\Utils\PostEntity;

class FilenameProviderTest extends TestCase
{
    public function testEntityPropertyFilenameProvider(): void
    {
        $resultWriter = new JsonFileResultWriter([
            'filename' => new EntityPropertyFilenameProvider([
                'property' => 'title',
            ]),
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);

        $this->writeResults($resultWriter);

        $files = array_keys(InMemoryOutputManager::$filesystem['test']);

        $this->assertEquals('Uno.json', $files[0]);
        $this->assertEquals('Dos.json', $files[1]);
        $this->assertEquals('Tres.json', $files[2]);
        $this->assertEquals('Cuatro.json', $files[3]);
    }

    public function testIncrementalFilenameProviderWithDefaultStart(): void
    {
        $resultWriter = new JsonFileResultWriter([
            'filename' => new IncrementalFilenameProvider(),
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);

        $this->writeResults($resultWriter);

        $files = array_keys(InMemoryOutputManager::$filesystem['test']);

        $this->assertEquals('1.json', $files[0]);
        $this->assertEquals('2.json', $files[1]);
        $this->assertEquals('3.json', $files[2]);
        $this->assertEquals('4.json', $files[3]);
    }

    public function testIncrementalFilenameProviderWithCustomStart(): void
    {
        $resultWriter = new JsonFileResultWriter([
            'filename' => new IncrementalFilenameProvider(['start' => 3]),
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);

        $this->writeResults($resultWriter);

        $files = array_keys(InMemoryOutputManager::$filesystem['test']);

        $this->assertEquals('3.json', $files[0]);
        $this->assertEquals('4.json', $files[1]);
        $this->assertEquals('5.json', $files[2]);
        $this->assertEquals('6.json', $files[3]);
    }

    public function testRandomFilenameProvider(): void
    {
        $resultWriter = new JsonFileResultWriter([
            'filename' => new RandomFilenameProvider(),
        ]);
        $resultWriter->setOutputManager(new InMemoryOutputManager('test'));
        $resultWriter->setLogWriter(new InMemoryLogWriter());
        $resultWriter->setEntity(PostEntity::class);

        $this->writeResults($resultWriter);

        $this->assertCount(4, InMemoryOutputManager::$filesystem['test']);
    }

    protected function writeResults(FileResultWriter $resultWriter): void
    {
        $first = new PostEntity();
        $first->setTitle('Uno');
        $first->setContent('First post');

        $second = new PostEntity();
        $second->setTitle('Dos');
        $second->setContent('Second post');

        $third = new PostEntity();
        $third->setTitle('Tres');
        $third->setContent('Third post');

        $fourth = new PostEntity();
        $fourth->setTitle('Cuatro');
        $fourth->setContent('Fourth post');

        $results = [
            $first,
            $second,
            $third,
            $fourth,
        ];

        foreach ($results as $result) {
            $resultWriter->setFilename($resultWriter->getFilenameProvider()->generateFilename($result));
            $resultWriter->write($result);
        }
    }
}
