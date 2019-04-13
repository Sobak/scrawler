# UrlListProvider Block
URL list provider is a type of block that is required for Scrawler to work properly,
thus at least one must be registered. The URLs provided by the block will be used
by Scrawler right after base URL so that it knows _where_ to crawl.

Therefore each URL list provider is provided with context of previous URL and last
response. You can define your own implementations to focus exactly on the URLs
you need to process.

URL list providers are registered using `addLogWriter()` method on the main
`Configuration` class.

```php
$configuration
    ->addUrlListProvider(new EmptyUrlListProvider())
;
```

## ArgumentAdvancerUrlListProvider
This URL list provider implementation allows to define the URL template in which
you want certain part of that URL to increase every time up to some point.

```php
public function __construct(string $template, int $start = 1, int $step = 1, ?int $stop = null)
```

As you can see, by default it starts with one and increments counter by one after
every processed URL. Incremental part is marked using `%u` in the URL you need to
provide.

```php
addUrlListProvider(new ArgumentAdvancerUrlListProvider('http://sobak.pl/page/%u', 2, 1, 17))
```

> **Note:** relative URLs are not yet supported.

In the example above Scrawler will head to `http://sobak.pl/page/2` after
processing the base URL, then it will increment the number up to 17 and finish
crawling.

> **Note:** due to current broken implementation Scrawler _will not_ stop on 404
> and will instead keep going forever. Therefore I suggest to _set_ the `$stop`
> argument. Thank me later!

## EmptyUrlListProvider
This URL returns empty list what ultimately makes Scrawler parse base URL only.
