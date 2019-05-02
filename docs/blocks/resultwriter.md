# ResultWriter Block
Result writers are responsible for the final step Scrawler performs,
persisting scrapped data. Since their underlying logic can be quite
complex they all accept configuration array. Basic configuration handling
is one of the things you can get by extending from `AbstractResultWriter`
class.

Result writers are set _per entity_ which means that all scrapped entity
types can be persisted in their own way.

## Meta: File result writers
This is just a meta section, not the block itself. There are a couple
of result writer implementations which ultimately write a file to your
disk. To encapsulate common logic they extend from the `FileResultWriter`
abstract class but also share some behavior.

First, they all accept `directory` configuration option which can be
passed to further specify results destination relative to the output
directory for your operation.

Second, they _need_ to accept a filename provider object under the
`filename` key. Read more about them in the
[dedicated documentation section](filenameprovider.md) and choose one
suitable to your needs.

## CsvFileResultWriter
This result writers will write each entity as a row of the CSV file. Aside from
all generic options for file result writers mentioned above it also accepts
`headers` array. Passing it will create one header row at the top of the file
and will make all entity properties being ordered consistently with headers,
otherwise properties will be ordered alphabetically.

```php
->addResultWriter(PostEntity::class, new CsvFileResultWriter([
    'filename' => new LiteralFilenameProvider(['filename' => 'posts']),
    'headers' => [
        'date' => 'Published on',
        'title' => 'Title',
        'slug' => 'Slug',
    ],
]))
```

> **Note:** since this result writer will create _one_ file for all of its
> entities you will probably want to use the `LiteralFilenameProvider` to
> get the filename.

Optionally you can customize any of the parameters supported by the underlying
`fputcsv()` function, being `delimiter`, `enclosure` and `escape_char`.

> **Note:** in order to have UTF-8 characters read properly by Microsoft Excel
> on Windows you will need a BOM character at the beginning of the file. You
> can tell Scrawler to insert it for you by setting `insert_bom` config option
> to `true` (`false` by default).

## DatabaseResultWriter
Saves results into almost any of the databases supported by PDO.

> **Note:** This writer depends on Doctrine, install it with
> `composer require doctrine/orm "^2.6"` first

In order to configure the database result writer pass connection parameters
parameters as `connection` key, accordingly to the
[Doctrine documentation][doctrine-connection]. Please note that the URL
connection string is not supported, you need to use an array.

There is an additional parameter `simple_annotations` which when set to
`true` parses Doctrine annotations without importing their namespace.

Sample configuration for the database result writer might then look like so:

```php
// MySQL
->addResultWriter(PostEntity::class, new DatabaseResultWriter([
    'connection' => [
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => '',
        'dbname'   => 'scrawler',
    ],
    'simple_annotations' => false,
]))

// SQLite
// Note that relative paths are resolved based on the location Scrawler's CLI
// was called at so it's more reliable to stick to the absolute paths.
->addResultWriter(PostEntity::class, new DatabaseResultWriter([
    'connection' => [
        'driver'   => 'pdo_sqlite',
        'path'     => '/var/scrawler/results.sqlite',
    ],
    'simple_annotations' => true,
]))
```

To use this writer you need to describe all entities used by it with
Doctrine [annotation mappings][doctrine-mapping]. Relationships are
not supported at the time of writing.

The database will be created if it doesn't exist and the user specified
in the connection has permissions to add new database. When running
Scrawler, all tables described by the entities assigned to this result
writer will be **dropped** and created from scratch with fresh data.

## DumpResultWriter
This is probably the simplest result writer available. All it does is
dumping the entity into the console. It tries to use the [VarDumper][vardumper]
component from Symfony to provide maximum readability, so you might
want to install it (`composer require symfony/var-dumper`) since otherwise
Scrawler will fall back to simple `var_dump()` call.

This result writer does not define any additional configuration options.

## InMemoryResultWriter
Another of simple result writer implementations and again suited mostly
for debugging and development. All prepared entities are stored in global
static `InMemoryResultWriter::$results` variable. Internally it is ued by
Scrawler for its own testing and you should not have a good reason to use
that writer, most of the cases will hugely benefit from less generic implemtation
even if all you need is to _do something_ with your results.

This result writer allows to pass an optional `group` setting which will
determine an array subkey all processed entities will be stored under.

## JsonFileResultWriter
This block implementation will store each of your entities into single
JSON file. Every entity property will be used as property name in the
resulting file.

> **Note:** This is a file result writer. You might want to read the
> general [section about them](#meta-file-result-writers).

Aside from options specific to all file result writers the JSON result writer
can accept `options` confiuration key which will be passed as options to the
`json_encode()` PHP function. The default value is
`JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE`.

## TemplateFileResultWriter
The most generic out of all file result writers. Does not have any particular
assumpions - instead it only takes template string where you can use variables
referencing fields defined by your entity getters.

Aside from specifying template string under `template` configuration key you
can also set the `extension`. Omitting that key or setting it explicitly to
`null` will result in files being created with no extension.

```php
->addResultWriter(PostEntity::class, new TemplateFileResultWriter([
    'directory' => 'posts/',
    'extension' => 'txt',
    'filename' => new IncrementalFilenameProvider(),
    'template' => 'Published at {{date}}'
]))
```

In real-life examples you will probably want to load the template from file and
keep it outside of your main configuration. Currently variable names are limited
to `[a-zA-Z_]` so it's better not to be too cratetive with getter names for this
use-case. You can insert any number tabs or spaces between variable names and
curly braces delimiting it. Finally, the variable with no counterpart in the
defined getters will result in the variable being printed directly into the
result file, like so: `Published at {{date}}`.

> **Note:** Even though this result writer can theoretically replace many others
> (you could even type JSON template by hand) you should use specialized writers
> whenever possible, including writing your own implementations.

[doctrine-connection]: https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/configuration.html
[doctrine-mapping]: https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/annotations-reference.html
[vardumper]: https://symfony.com/doc/current/components/var_dumper.html
