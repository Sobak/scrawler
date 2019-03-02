<?php

namespace Tests\Functional\Crawling;

use Sobak\Scrawler\Block\FieldDefinition\StringField;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\ArgumentAdvancerUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Tests\Utils\SimpleMatchEntity;

$host = 'http://127.0.0.1:' . getenv('PHP_SERVER_PORT');

$scrawler = new Configuration();

$scrawler
    ->setOperationName('test')
    ->setBaseUrl($host)
    ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("{$host}/page-%u.html", 2, 2, 4))
    ->addObjectDefinition('message', new CssSelectorListMatcher('div.message'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('match', new StringField(new CssSelectorTextMatcher('span.content')))
            ->addEntityMapping(SimpleMatchEntity::class)
            ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
        ;
    })
;

return $scrawler;
