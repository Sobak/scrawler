<?php

namespace Tests\Functional\LogWriter;

use Psr\Log\LogLevel;
use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

class TextfileLogWriterTest extends ServerBasedTest
{
    public function testWritingLogToFile(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl())
            ->addLogWriter(new TextfileLogWriter())
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorTextMatcher('span.match'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $log = file(__DIR__ . '/output/test/crawler.log');
        $this->assertRegExp('#\[INFO\] Running "test" operation#', $log[0]);
    }

    public function testSettingVerbosityLevels(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl())
            ->addLogWriter(new TextfileLogWriter('full.log'))
            ->addLogWriter(new TextfileLogWriter('notice.log'), LogLevel::NOTICE)
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorTextMatcher('span.match'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $log = file(__DIR__ . '/output/test/full.log');
        $this->assertRegExp('#\[INFO\] Running "test" operation#', $log[0]);

        $this->assertFalse(file_exists(__DIR__ . '/output/notice.log'));
    }

    public function testTwoLogWritersWithSameVerbosity(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl())
            ->addLogWriter(new TextfileLogWriter('first.log'))
            ->addLogWriter(new TextfileLogWriter('second.log'))
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorTextMatcher('span.match'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $log = file(__DIR__ . '/output/test/first.log');
        $this->assertRegExp('#\[INFO\] Running "test" operation#', $log[0]);

        $log = file(__DIR__ . '/output/test/second.log');
        $this->assertRegExp('#\[INFO\] Running "test" operation#', $log[0]);
    }
}
