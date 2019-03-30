# Configuration
Scrawler is a declarative software which means most of its behavior is driven by
the provided configuration. That's why you will need to read that chapter (or the
code) to really get into using Scrawler.

When using Scrawler by calling the builtin commandline application you will need
to provide path to the configuration file. This file must _return_ the `Scrawler`
object.

There are two types of options: ones you simply set to certain value (using setters
of the `Configuration` class) and _things_ that you add to your configuration.
Scrawler customization is built primarily around _blocks_ which are the swappable
pieces of logic responsible for handling certain parts of the crawling, scrapping
and result processing operations. You will learn more on [Blocks](blocks.md).

## Basic configuration
There are a couple of configuration options that you need to provide:

- **baseUrl** - it's the URL Scrawler will start crawling from. Must be absolute.
- **operationName** - it is your name for the particular configuration set for
  crawling, like _"Homepage scrapping"_. It will be used to name the output
  directory, appear in logs etc.

In order to set these options you will need to call setter methods on the
`Configuration` object - `setBaseUrl()` and accordingly for other options.

There are also following types of blocks that you can provide for the top-level
configuration:
- **[Client configuration provider](blocks/clientconfigurationprovider.md)**
- **URL list provider** - you have to add at least one implementation for URL
  list provider block so that Scrawler will know what URLs should it crawl to
  after it's done with your base URL.

Adding blocks (either top-level or for objects described below) usually follows
the same API pattern:

```php
addBlockName(new SomeBlockImplementation(...)); // to use certain implementation for Block
removeBlockName(SomeBlockImplementation::class); // to remove previously added block implementation 
```

Method for adding new block implementation can sometimes accept string where you
have to put any textual identifier for the particular block implementation
instance. This happens when object of same class can be used many times depending
on its configuration (so typically parameters passed to the constructor).
This may sound vague but once you start writing documentation and following code
signatures etc it should get pretty straightforward.

## Objects
Object in Scrawler is a most basic unit of what you will scrap from the website.
Each object must consist of fields like post may consists of content and title.
Of course this is just an example. You will need to determine the patterns for
data you are interested with on particular website.

In most cases there will be multiple instances of given object find on a website
(e.g. on per page or even multiple on every page). It may also happen, though,
that some pieces of information you want to get will be global for the whole website
(e.g. its header and description). Just to clarify, you will still need to describe
them as an object.

Objects are added using `addObjectDefinition` on the `Configuration` object and
their own configuration is provided using a clousure. As stated above an object
must consist of at least one field which is then added using `addFieldDefinition`
method of the `ObjectConfiguration` class (passed as an argument to the closure).

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
    ->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('date', new CssSelectorTextMatcher('time.entry-date'))
    
    // ...
    
        ;
    })
;
```

As you can see above, aside from the object name (which is used just for further
reference like removing that object from the configuration and can be anything
you want) and the closure object takes one more argument. This is a matcher
instance. Matchers (described in the [Blocks](blocks.md) part of the documentation)
are responsible for returning information from the current page based on specific
criteria given. For objects you will need to use matchers that return a list of
matches (class names ending with `ListMatcher`) even if it would be just a one
element list like for the website header/description example given above.

### Object fields
As you can see configuration for an object field is quite similar to the config
for the object itself. The main difference is that the matcher used there needs
to return single element representing that field.

### Entity mapping
Once you have defined the fields your object of information have you will need
to provide an entity name you want to map it to. Entities are PHP objects that
keep and map scrapped results. In many cases they are the only thing that you
will need to write yourself while using builtin classes for everything else.
You can map single object to multiple entities and by using different setters,
getters and even property names you can map same information in couple of
different ways. Entities are covered in detail in [next chapter](entities.md).

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
    ->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('date', new CssSelectorTextMatcher('time.entry-date'))
            ->addEntityMapping(PostEntity::class)
    
    // ...
    
        ;
    })
;
```

### Result writers
Alright, you have defined patterns for the information you are interested in, you
have mapped that information to PHP objects, now it's probably time to finally do
something useful with that information. That's where result writers come in. They
are described in a [separate chapter](blocks/resultwriter.md) and there are many
types of result writers available but to have an example we'll use something simple
for now:

```php
return (new Configuration())
    ->setOperationName('Sample config')
    ->setBaseUrl('http://sobak.pl')
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
in around ten lines of code. You should of course continue reading this documentation
to do something more useful than dumping information on the screen and e.g. write
it to the database instead.
