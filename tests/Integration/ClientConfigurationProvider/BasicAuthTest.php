<?php

namespace Tests\Integration\ClientConfigurationProvider;

use Sobak\Scrawler\Block\ClientConfigurationProvider\BasicAuthProvider;
use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Block\ClientConfigurationProvider\BasicAuthProvider
 */
class BasicAuthTest extends IntegrationTest
{
    public function testBasicAuth(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/basicauth.php')
            ->addClientConfigurationProvider(new BasicAuthProvider('test', 'password'))
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('.result'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertEquals('OK', InMemoryResultWriter::$results[0]['match']);
    }
}
