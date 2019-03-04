# Blocks
Blocks are the main building components of Scrawler which are
responsible for providing swappable pieces of logic defining
the way crawling, scrapping or result processing operations
work. Usually there is more than one implementation provided by
default for given block which you can (and should) easily pick
in your configuration file, depending on your needs.

Internally, all blocks are also structured in a similar way.
They live in `\Scrawler\Block` namespace and consist of an
interface that each block variant must implement and in most
cases a general abstract class which provides most typical
behavior (usually limited to implementing required setters or
getters and their corresponding class properties).

For the list of available blocks and their builtin implementations
please consult the list below. Please note that first level list
items lead to the general documentation for the block — its interface,
purpose and related implementation details while nested elements are
for description of every class that is provided out of the box,
mentioning its potential parameters etc.

- [ClientConfigurationProvider](blocks/clientconfigurationprovider.md)
	- [ScrawlerUserAgentProvider](blocks/clientconfigurationprovider.md#scrawleruseragentprovider)
- FieldDefinition
	- IntegerField
	- NullableIntegerField
	- StringField
	- NullableStringField
- LogWriter
	- ConsoleLogWriter
	- TextfileLogWriter
- ResultWriter
	- DumpResultWriter
	- InMemoryResultWriter
	- JsonFileResultWriter
- UrlListProvider
	- ArgumentAdvancerUrlListProvider
	- EmptyUrlListProvider
