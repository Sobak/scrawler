<?php

declare(strict_types=1);

namespace Tests\Integration\Http;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\EmptyUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\InMemoryLogWriter;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Client\Response\ContentType
 * @covers \Sobak\Scrawler\Scrawler
 */
class ContentTypeTest extends IntegrationTest
{
    public function testCrawlingXmlContent(): void
    {
        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/movies.xml')
            ->addUrlListProvider(new EmptyUrlListProvider())
            ->addObjectDefinition('movie', new CssSelectorListMatcher('movie'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('title'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertEquals('First', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third', InMemoryResultWriter::$results[2]['match']);
    }

    public function testCrawlingUnsupportedContentType(): void
    {
        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/file.txt')
            ->addLogWriter(new InMemoryLogWriter())
            ->addUrlListProvider(new EmptyUrlListProvider())
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertRegExp('#Skipped due to unprocessable content type \(text/plain\)#', InMemoryLogWriter::$log[1]);
    }
}
