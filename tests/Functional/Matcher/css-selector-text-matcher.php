<?php

declare(strict_types=1);

namespace Tests\Functional\Matcher;

use Sobak\Scrawler\Block\FieldDefinition\StringField;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\EmptyUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Tests\Utils\SimpleMatchEntity;

$scrawler = new Configuration();

$scrawler
    ->setOperationName('test')
    ->setBaseUrl('http://127.0.0.1:' . getenv('PHP_SERVER_PORT'))
    ->addUrlListProvider(new EmptyUrlListProvider())
    ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('match', new StringField(new CssSelectorTextMatcher('span.match')))
            ->addEntityMapping(SimpleMatchEntity::class)
            ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
        ;
    })
;

return $scrawler;
