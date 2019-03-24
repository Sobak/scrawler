# Changelog
All notable changes to Scrawler will be documented in this file.

## [Unreleased]
### Added
- Added database result writer

### Changed
- Added `getEntity` and `setEntity` methods to the `ResultWriterInterface` to
  set the entity context for the result writer to use.
- Added `initializeResultWrites` method to the `ResultWriterInterface` to allow
  hooking with any kind of preparation logic for every result writer.

### Fixed
- Fixed misleading default values for required configuration options which confused
  some of the code analysys tools

## 0.1.0 - 2019-03-04
Initial release of Scrawler.

[Unreleased]: https://github.com/Sobak/scrawler/compare/v0.1.0...develop
