<?php

namespace Tests\Integration\Http;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\InMemoryLogWriter;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Client\Response\StatusCode
 * @covers \Sobak\Scrawler\Scrawler
 */
class StatusCodeTest extends IntegrationTest
{
    public function testNonProcessableBaseUrl(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/non-existent')
            ->addLogWriter(new InMemoryLogWriter())
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('span.match'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertRegExp('#Skipped due to unprocessable response code#', InMemoryLogWriter::$log[1]);
    }
}
