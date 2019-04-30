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

Each URL list provider object is fed with useful data before `getUrls()` method
is called such as base and current URL and the currently processed response
object. In most cases you will want to use this data to obtain list of next
URLs for your custom implementation.

> **Note:** when trying to read response's content please remember it is a
> _stream_ so you need to `rewind()` it before reading, otherwise you will
> get empty string as it has been read before.

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

You can also use relative URL in the template — in such case your base URL
will be prepended to it so the repetition can be avoided:

```php
addUrlListProvider(new ArgumentAdvancerUrlListProvider('/page/%u', 2, 1))
```

In the example above Scrawler will head to `http://sobak.pl/page/2` after
processing the base URL, then it will continue increasing the number until
server responds with HTTP 404.

## EmptyUrlListProvider
This URL returns empty list what ultimately makes Scrawler parse base URL only.

## SameDomainUrlListProvider
Looks for `href` attributes of `a` tags and returns links with the domain
matching domain of current operation's base URL. Gathered URLs are normalized
(resolving relative paths, protocols, removing anchors etc) before the domain
comparison is made.
