<?php

namespace Tests\Functional\ClientConfigurationProvider;

use Sobak\Scrawler\Block\ClientConfigurationProvider\BasicAuthProvider;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

class BasicAuthTest extends ServerBasedTest
{
    public function testClassMatching(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl() . '/basicauth.php')
            ->addClientConfigurationProvider(new BasicAuthProvider('test', 'password'))
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorTextMatcher('.result'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();

        $this->assertEquals('OK', InMemoryResultWriter::$results[0]['match']);
    }
}
