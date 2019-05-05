<?php

namespace Tests\Integration\LogWriter;

use Psr\Log\LogLevel;
use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;
use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Block\LogWriter\TextfileLogWriter
 * @covers \Sobak\Scrawler\Configuration\Configuration
 * @covers \Sobak\Scrawler\Support\LogWriter
 * @covers \Sobak\Scrawler\Output\Outputter
 */
class TextfileLogWriterTest extends IntegrationTest
{
    public function testWritingLogToFile(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addLogWriter(new TextfileLogWriter())
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

        $log = file(__DIR__ . '/output/test/crawler.log');
        $this->assertRegExp('#\[NOTICE\] Started "test" operation#', $log[0]);
    }

    public function testSettingVerbosityLevels(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addLogWriter(new TextfileLogWriter('full.log'))
            ->addLogWriter(new TextfileLogWriter('warning.log'), LogLevel::WARNING)
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

        $log = file(__DIR__ . '/output/test/full.log');
        $this->assertRegExp('#\[NOTICE\] Started "test" operation#', $log[0]);

        $this->assertFalse(file_exists(__DIR__ . '/output/warning.log'));
    }

    public function testTwoLogWritersWithSameVerbosity(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addLogWriter(new TextfileLogWriter('first.log'))
            ->addLogWriter(new TextfileLogWriter('second.log'))
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

        $log = file(__DIR__ . '/output/test/first.log');
        $this->assertRegExp('#\[NOTICE\] Started "test" operation#', $log[0]);

        $log = file(__DIR__ . '/output/test/second.log');
        $this->assertRegExp('#\[NOTICE\] Started "test" operation#', $log[0]);
    }
}
