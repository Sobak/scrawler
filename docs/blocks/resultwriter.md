# ResultWriter Block
Result writers are responsible for the final step Scrawler performs,
persisting scrapped data. Since their underlying logic can be quite
complex they accept all configuration array. Basic configuration handling
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

## DatabaseResultWriter
Saves results into almost any of the databases supported by PDO.

> **Note:** This writer depends on Doctrine, install it with
> `composer require doctrine/orm "^2.6"` first

In order to configure the database result writer pass connection parameters
parameters as `connection` key, accordingly to the
[Doctrine documentation][doctrine-connection]. Please note that the URL
connection string is not supported, you need to use an array.

> **Note:** SQLite support is not available yet.

There is an additional parameter `simple_annotations` which when set to
`true` parses Doctrine annotations without importing their namespace.

Sample configuration for the database result writer might then look like so:

```php
->addResultWriter(PostEntity::class, new DatabaseResultWriter([
    'connection' => [
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => '',
        'dbname'   => 'scrawler',
    ],
    'simple_annotations' => false,
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

This result writer does not define any additional configuration options
aside from ones specific to all file result writers.

[doctrine-connection]: https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/configuration.html
[doctrine-mapping]: https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/annotations-reference.html
[vardumper]: https://symfony.com/doc/current/components/var_dumper.html
