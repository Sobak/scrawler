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
every processed URL. Incremental part is marked using `%u` in the URL template.
When you omit the `$stop` parameter URLs will be incremented to the point where
HTTP 404 is received from the server. Note that it will cause entry into your logs
that you should not worry about.

```php
addUrlListProvider(new ArgumentAdvancerUrlListProvider('http://sobak.pl/page/%u', 2, 1))
```

> **Note:** relative URLs are not yet supported.

In the example above Scrawler will head to `http://sobak.pl/page/2` after
processing the base URL, then it will continue increasing the number until
server responds with HTTP 404.

## EmptyUrlListProvider
This URL returns empty list what ultimately makes Scrawler parse base URL only.
