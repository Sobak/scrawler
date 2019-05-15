# Cookbook: writing parts of an object to different storages
Examples shown in the documentation so far present somehow simple flow regarding
the results where object we define is first mapped over the user-provided entity
and then written down to the storage of choice, like a database or set of JSON
files. Inside the entity classes we define every field of an object, then create
getters and setters for each of them and that's more or less it.

However, this definitely isn't all Scrawler can do. We can notice that result writers
are not _set_ (so exchanged one for another) but _added_ to the stack. This is done to
allow defining multiple objects, each having their own result writer _but_ it is also
done so that same object (and thus its resulting entity) can be written multiple times
using different logic (contained in Blocks).

For this study case we'll scrap posts from my blog but instead of using single
writer for an object wel'll save posts' metadata into single CSV file while the
content of each post will be kept inside individual HTML file.

```php
<?php

declare(strict_types=1);

use App\PostContentEntity;
use App\PostMetaEntity;
use Sobak\Scrawler\Block\ClientConfigurationProvider\ScrawlerUserAgentProvider;
use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\LiteralFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\CsvFileResultWriter;
use Sobak\Scrawler\Block\ResultWriter\TemplateFileResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\ArgumentAdvancerUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\DefaultConfigurationProvider;
use Sobak\Scrawler\Configuration\ObjectConfiguration;

require 'vendor/autoload.php';

$scrawler = new Configuration();
$scrawler = (new DefaultConfigurationProvider())->setDefaultConfiguration($scrawler);

$scrawler
    ->setOperationName('Blog posts')
    ->setBaseUrl('http://sobak.pl')
    ->addClientConfigurationProvider(new ScrawlerUserAgentProvider('Scrawler Test', 'http://sobak.pl'))
    ->addUrlListProvider(new ArgumentAdvancerUrlListProvider('/page/%u', 2))
    ->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('date', new CssSelectorHtmlMatcher('time.entry-date'))
            ->addFieldDefinition('content', new CssSelectorHtmlMatcher('div.entry-content'))
            ->addFieldDefinition('slug', new CssSelectorHtmlMatcher('h1.entry-title a[href]'))
            ->addFieldDefinition('title', new CssSelectorHtmlMatcher('h1.entry-title a'))
            ->addEntityMapping(PostContentEntity::class)
            ->addEntityMapping(PostMetaEntity::class)
            ->addResultWriter(PostMetaEntity::class, new CsvFileResultWriter([
                'filename' => new LiteralFilenameProvider(['filename' => 'posts']),
                'insert_bom' => true,
            ]))
            ->addResultWriter(PostContentEntity::class, new TemplateFileResultWriter([
                'directory' => 'posts/',
                'extension' => 'html',
                'filename' => new IncrementalFilenameProvider(),
                'template' => '{{content}}'
            ]))
        ;
    })
;

return $scrawler;
```

Contrary to the earlier examples we define two entities to represent the post, each
consisting of different fields. Then we will assign different type of result writer
to each entity to achieve the desired effect.

> **Note:** result writer will attempt to write every field it will find a getter for.
> Therefore in order to only write the subset of an object data we need the separate
> entity.

The entity that will represent post metadata will look like so:

```php
<?php

declare(strict_types=1);

namespace App;

class PostMetaEntity
{
    protected $date;

    protected $slug;

    protected $title;

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
}
```

Notice the lack of the `content` field. That way it will not be written into the CSV
file (where it would perhaps decrease its readability and be hard to utilize in any
way). Instead we will create the dedicated entity to hold the content, that will be
later handled by the `TemplateFileResultWriter` putting it into HTML files.

```php
<?php

declare(strict_types=1);

namespace App;

class PostContentEntity
{
    protected $content;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}
```

> **Note:** being strict, we could use single entity for this case because
> [`TemplateFileResultWriter`](../blocks/resultwriter.md#templatefileresultwriter)
> accepts placeholder of fields it puts into the file… Nonetheless, treat
> this writeup as a general advice on how to write parts of results to many
> different places.
