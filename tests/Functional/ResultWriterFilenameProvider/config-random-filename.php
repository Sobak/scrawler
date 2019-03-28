<?php

declare(strict_types=1);

namespace Tests\Functional\Crawling;

use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\RandomFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\EmptyUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\SimpleMatchEntity;

$scrawler = new Configuration();

$scrawler
    ->setOperationName('test')
    ->setBaseUrl(ServerBasedTest::getHostUrl())
    ->addUrlListProvider(new EmptyUrlListProvider())
    ->addObjectDefinition('message', new CssSelectorListMatcher('div.message'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('match', new CssSelectorTextMatcher('span.content'))
            ->addEntityMapping(SimpleMatchEntity::class)
            ->addResultWriter(SimpleMatchEntity::class, new JsonFileResultWriter([
                'filename' => new RandomFilenameProvider(),
            ]))
        ;
    })
;

return $scrawler;
