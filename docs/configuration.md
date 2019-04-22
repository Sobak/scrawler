# Configuration
As mentioned previously the configuration for `scrawler` — the default
commandline entrypoint — should live in a file and should be object of class
`Configuration` being returned by that file.

> **Note:** make sure to read the [getting started](getting-started.md) chapter
> first to have understanding of basic Scrawler's concepts.

## Basic options
The basic options only take single value which is provided using classing PHP
setter method.

### `setBaseUrl()`
Setting this value is **mandatory**. It should be an absolute URL Scrawler will
start crawling from.

### `setOperationName()`
Setting this value is **mandatory**. This can be any string that will uniquely
define your configuration set. However, it will be used to name then output
directory, will appear in the logs etc, so it makes sense to set it to something
meaningful.

### `setMaxCrawledUrls()`
The `maxCrawledUrls` option specifies a number of unique URLs Scrawler will
process. Omitting this option or setting it to `0` means that all the available
URLs (given by registered URL list providers) will be fetched and parsed.

## Default configuration
In order to apply set of recommened configuration options you should pass your
`Configuration` into `DefaultConfigurationProvider` class where it will be
modified by the reference. Be sure to review what the default options are.

```php
$scrawler = new Configuration();
$scrawler = (new DefaultConfigurationProvider())->setDefaultConfiguration($scrawler);

$scrawler
    ->setOperationName('Test config')
    // ...
;
```

## Blocks
Idea of blocks was described in the previous chapter so we will skip the theory
now. In context of configuration you need to know that you can add infinite
number implementations for any block type to configure your operation.

Adding blocks (either top-level or for objects described below) usually follows
the same API pattern:

```php
addBlockName(new SomeBlockImplementation(...));
```

For the top-level configuration (outside of blocks) you can use the following
block types:
- [Client configuration provider](blocks/clientconfigurationprovider.md)
- [URL list provider](blocks/urllistprovider.md)

For example:

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
    ->addUrlListProvider(new EmptyUrlListProvider())
;
```

## Objects
Objects are added using `addObjectDefinition` on the `Configuration` object and
their own configuration is provided using a clousure. Each object must consist
of at least one field which is then added using `addFieldDefinition` method of
the `ObjectConfiguration` class (available as the argument of the closure).

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
    ->addUrlListProvider(new EmptyUrlListProvider())
    ->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('date', new CssSelectorTextMatcher('time.entry-date'))
        ;
    })
;
```

As you can see above, aside from the object name (which can be any unique string)
and the closure, you need to provide one more element when describing objects.
This is a matcher instance. Matchers (described in the [Blocks](blocks.md) part
of the documentation) are responsible for picking out information from the current
page. There are two main types of matchers: ones returning single element and the
list of elements. For objects you will need to use the latter even if you expect
just one match for the object in the course of entire operation (just like website
header/description example given in previous chapter).

### Object fields
As you can see configuration for an object field is quite similar to the config
for the object itself. The main difference is that the matcher used there needs
to return single element representing that field.

> **Note:** all matching criteria for object fields are considered in the scope
> of object itself. In the example above `time.entry-date` will only be matched
> inside a specific object which is being parsed at the moment so you don't have
> to repeat matching criteria (e.g. using `article.hentry time.entry-date`).

### Entity mapping
Next you have to provide entity object results will be mapped to. Entity classes
are defined by you, serve mostly as simple Data Transfer Objects and have been
discussed separately in the [next chapter](entities.md).

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
    ->addUrlListProvider(new EmptyUrlListProvider())
    ->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('date', new CssSelectorTextMatcher('time.entry-date'))
            // obviously you can define more fields...
            ->addEntityMapping(PostEntity::class)
        ;
    })
;
```

### Result writers
The last piece of the puzzle is providing the class(es) responsible for saving
the results. Since result writers are _de facto_ blocks you add them using
`addResultWriter()` method. As the name implies you can register multiple result
writers — you can write different entities each in their own way or even write
single entity to multiple destinations.

There is a [separate chapter](blocks/resultwriter.md) describing available result
writers and their configuration but let's finish our example with something simple:

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
    ->addUrlListProvider(new EmptyUrlListProvider())
    ->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('date', new CssSelectorTextMatcher('time.entry-date'))
            ->addEntityMapping(PostEntity::class)
            ->addResultWriter(PostEntity::class, new DumpResultWriter())    
        ;
    })
;
```

That should finally give us something. There is a lot of information in this
chapter but as you can see you can dump dates of all posts from my blog homepage
in around ten lines of code.

You should of course continue reading this documentation to do something more
useful than dumping dates on the screen…
