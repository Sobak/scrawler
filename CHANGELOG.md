# Changelog
All notable changes to Scrawler will be documented in this file.

## [Unreleased]
### Added
- Added database result writer
- Added template file result writer
- Added incremental filename provider
- Added literal filename provider
- Added HTTP Basic Auth client configuration provider
- Added suport for registering multiple result writers per entity
- Added possibility to specify output directory using `crawler` CLI
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
- Loggers _must_ implement PSR-3 interface now  
- `Scrawler` now accepts `Configuration` object plus the output directory instead
  of configuration path
- `ScrawlerUserAgentProvider` now requires final bot name and contact URL.
- Splitted `MatcherInterface` into `SingleMatcherInterface` and `ListMatcherInferface`,
  each matcher must implement more precise interface now depending on whether it
  is used for matching blocks or fields

### Fixed
- Fixed misleading default values for required configuration options which confused
  some of the code analysys tools

### Removed  
- `FieldDefinition` classes
- Removing block, objects and field definitions at runtime (using `Configuration`s
  or `ObjectConfiguration`s `remove*()` methods) is no longer supported

## 0.1.0 - 2019-03-04
Initial release of Scrawler.

[Unreleased]: https://github.com/Sobak/scrawler/compare/v0.1.0...develop
