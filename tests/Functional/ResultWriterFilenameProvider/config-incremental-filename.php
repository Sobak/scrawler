<?php

declare(strict_types=1);

namespace Tests\Functional\Crawling;

use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\EmptyUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\PostEntity;

$scrawler = new Configuration();

$scrawler
    ->setOperationName('test')
    ->setBaseUrl(ServerBasedTest::getHostUrl() . '/posts.html')
    ->addUrlListProvider(new EmptyUrlListProvider())
    ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('content', new CssSelectorTextMatcher('span.content'))
            ->addFieldDefinition('title', new CssSelectorTextMatcher('h2'))
            ->addEntityMapping(PostEntity::class)
            ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                'filename' => new IncrementalFilenameProvider(),
            ]))
        ;
    })
;

return $scrawler;
