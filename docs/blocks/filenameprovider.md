# FilenameProvider Block
Filename providers are used in context of [result writers](resultwriter.md)
that persist information to your disk drive. When configuring such class
you need to set one of the filename providers using `filename` configuration
option.

Their responsibility is, obviously, to return a string filename and they do
so **excluding** the extension so it can be further specified by a particular
result writer.

## EntityPropertyFilenameProvider
This filename provider takes entity object and uses one of its properties as
a filename. For example to save scrapped posts into JSON files and use their
slugs as filenames one might use:

```php
->addResultWriter(PostEntity::class, new JsonFileResultWriter([
    'filename' => new EntityPropertyFilenameProvider([
        'property' => 'slug',
    ]),
]))
```

## IncrementalFilenameProvider
With this filename provider every result (entity) will be written into the file
with incremental name. By default it starts from `1` but there is an optional
configuration parameter `start` using which you can change that value.

## LiteralFilenameProvider
The filename will be the literal value passed under the mandatory `filename`
configuration key. Remember it will probably only be useful for the objects
that are found just once when you run Scrawler, otherwise results will be
overriden and only last one will be available in the file with given name.

> **Note:** This is still a _filename_ provider, specifying the extension is
> the responsibility of the file result writer you will use it for.

## RandomFilenameProvider
Every entity will be saved into the file with random name. This provider **does not**
guarantee name uniqueness so it might happen that names will collide and some results
will override.

The PHP's `uniqueid()` function is used under the hood.
