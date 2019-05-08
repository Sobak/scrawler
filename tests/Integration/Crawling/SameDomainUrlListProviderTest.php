<?php

declare(strict_types=1);

namespace Tests\Integration\Crawling;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\UrlListProvider\SameDomainUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\InMemoryResultWriter;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher
 * @covers \Sobak\Scrawler\Block\UrlListProvider\AbstractUrlListProvider
 * @covers \Sobak\Scrawler\Block\UrlListProvider\SameDomainUrlListProvider
 * @covers \Sobak\Scrawler\Configuration\Configuration
 * @covers \Sobak\Scrawler\Configuration\ConfigurationChecker
 * @covers \Sobak\Scrawler\Configuration\ObjectConfiguration
 * @covers \Sobak\Scrawler\Scrawler
 */
class SameDomainUrlListProviderTest extends IntegrationTest
{
    public function testSameDomainUrlListProvider(): void
    {
        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addUrlListProvider(new SameDomainUrlListProvider())
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.message'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('span.content'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertCount(7, InMemoryResultWriter::$results);
        $this->assertCount(7, array_unique(array_column(InMemoryResultWriter::$results, 'match')));
    }
}
