# Changelog
All notable changes to Scrawler will be documented in this file.

## [Unreleased]
### Added
- Added robots.txt parser block
- Added support for resolving relative URLs
- Added CSV file result writer
- Added `SameDomainUrlListProvider`
- Added support for writing results into SQLite databases
- Added literal client configuration provider
- Added total time displayed once operation is done
- Added ability to set maximum number of crawled URLs
- Added a warning when empty response is encountered
- Added ability to mark objects as fetched only once per operation
- Added ability to pass encoding options to JSON file result writer
- Added support for relative URL in `ArgumentAdvancerUrlListProvider`'s template
- Added `TEST_SERVER_WAIT` environment variable to change default wait time for
  the server used in integration tests

### Changed
- `CssSelectorTextMatcher` and `XpathSelectorTextMatcher` are now renamed to
  `CssSelectorHtmlMatcher` and `XpathSelectorHtmlMatcher` accordingly and will
  return original HTML content instead of textual form, making them consistent
  with other matchers like regular expression matcher. To retain previous
  behavior one should strip the tags further down the line (e.g. in entities)
- `RegexTextMatcher` has been renamed to `RegexHtmlMatcher`
- Underlying Guzzle instance will _always_ depend on cURL now. This is done to
  ensure that widest set of features is available for handling HTTP requests.
- Scrawler will now explicitly emit a warning for content types other than XML or (X)HTML
- `DefaultConfigurationProvider` sets timeouts for Guzzle now
- `JSON_UNESCAPED_UNICODE` option is now used by default when using JSON file
  result writer
- The `simple_annotations` options for the database result writer is now `false`
  by default. Previously it had to be specified explicitly.
- Only HTTP and HTTPS protocols are now explicitly allowed
- Improved performance of CSS selector matchers
- Improved handling of networking errors
- Improved logs readability
- Changed default log verbosity for both console and the textfile to `INFO` level
- `PHP_SERVER_PORT` environment variable used to set the port of the webserver
  used to run integration tests has been renamed to `TEST_SERVER_PORT`

### Fixed
- Fixed incorrect detecting of visited URLs resulting in some adresses being
  processed multiple times
- Fixed incorrect whitespace trimming in text matchers

### Removed
- Removed `InMemoryResultWriter` from the blocks list - now it is only available
  during development to run tests

### Known issues
- Relative URLs with paths using `..` are not yet supported

## [0.2.0] - 2019-04-13
### Added
- Added basic capabilities of handling non-200 responses
- Added database result writer
- Added template file result writer
- Added incremental filename provider
- Added literal filename provider
- Added HTTP Basic Auth client configuration provider
- Added ability to register more than one URL list provider
- Added ability to set the filename for text file log writer
- Added support for registering multiple result writers per entity
- Added ability to set minimal verbosity level for each log writer
- Added possibility to specify output directory using `scrawler` CLI
- Added logging capabilities for more elements of the script lifecycle
- Added confirmation on overriding output directory (can be forced with `--force`/`-f` option)

### Changed
- Added `getEntity` and `setEntity` methods to the `ResultWriterInterface` to
  set the entity context for the result writer to use
- Added `initializeResultWrites` method to the `ResultWriterInterface` to allow
  hooking with any kind of preparation logic for every result writer
- Entities should no longer extend `AbstractEntity` nor implement `EntityInterface`.
  They can be plain PHP objects and aforementioned classes have been removed. That
  also means that entity mapping is now done by the Scrawler and you should use
  getters and setters to implement any custom mapping logic.
- File result writers can now pass `null` as the extension into the `writeToFile()`
  method in order to create files without extension
- ArgumentAdvancerUrlListProvider stops on first received 404 error
- Loggers _must_ implement PSR-3 interface now  
- `Scrawler` now accepts `Configuration` object plus the output directory instead
  of configuration path
- `ScrawlerUserAgentProvider` now requires final bot name and contact URL.
- Splitted `MatcherInterface` into `SingleMatcherInterface` and `ListMatcherInferface`,
  each matcher must implement more precise interface now depending on whether it
  is used for matching blocks or fields

### Fixed
- Fixed misleading default values for required configuration options which confused
  some of the code analysis tools

### Removed  
- `FieldDefinition` classes
- Removing block, objects and field definitions at runtime (using `Configuration`s
  or `ObjectConfiguration`s `remove*()` methods) is no longer supported

## 0.1.0 - 2019-03-04
Initial release of Scrawler.

[0.2.0]: https://github.com/Sobak/scrawler/compare/v0.1.0...v0.2.0
[Unreleased]: https://github.com/Sobak/scrawler/compare/v0.2.0...develop
