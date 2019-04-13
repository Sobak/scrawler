# LogWriter Block
Log writers are blocks responsible exactly for what their name suggests, writing
down the logs. Scrawler supports registering multiple log writers at the same
time allowing to have information written e.g. to both console and the text file.

Furthermore, you can set verbosity level per logger so that scenarios in which
console only shows important messages while textfile keeps everything including
debug data are fully achievable. You could also have two text files with different
details level each.

Finally, Scrawler follows [PSR-3][PSR-3] so you can utilize various popular logger
implementations for PHP, like Monolog.

> **Note:** when using third-party loggers it's advisable to use Scrawler's ability
> to set verbosity level, even if the external library has such capability, so that
> handling is consistent.

Log writers are registered using `addLogWriter()` method on the main `Configuration`
class.

```php
$configuration
    ->addLogWriter(new ConsoleLogWriter(), LogLevel::NOTICE)
    ->addLogWriter(new TextfileLogWriter())
;
```

Note that settings shown above are default as long as you use the `DefaultConfigurationProvider`.
Console log writer only accepts messages of level `NOTICE` or higher so that
your terminal is not cluttered with less useful info.

> **Note:** using less verbose logging (or less log writers) may slightly improve
> Scrawler's performance

## ConsoleLogWriter
This log writer outputs to the default interface of the commandline interface
and has no special options.

## TextfileLogWriter
Saves logs into the text file in the output directory. Optionally you can pass
the filename as the second parameter. Otherwise `crawler.log` will be used.

[PSR-3]: https://www.php-fig.org/psr/psr-3/
