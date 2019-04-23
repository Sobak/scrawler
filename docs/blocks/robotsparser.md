# RobotsParser Block
Robots parser blocks are responsible for parsing bot-specific restrictions set
by the website creators. However, unlike other blocks, you can only set one
robots parser since you ultimately need just the boolean value deciding whether
the URL is crawlable or not. Therefore the robots parser implementation can be
chosen using simple `setRobotsParser()` call on the `Configuration` object.

```php
$configuration
    ->setRobotsParser(new DefaultRobotsParser())
;
```

You can set your useragent fragment which will be used to check vendor-specific
settings in `robots.txt` file. To do so pass the optional parameter to the
robots parser's constructor. Otherwise the useragent will be set to `Scrawler`
meaning it will match any bot name including `Scrawler` in its name and the
asterisk (`*`) settings from `robots.txt`.

Finally please note that configuration shown above is the default one as long
as you use the `DefaultConfigurationProvider`. You can use no robots parser but
be aware that it might result in your bot being blocked completely for trying
to bypass the rules.

## DefaultRobotsParser
This is just the thin wrapper on a third party library which aims to follow
Googlebot and Yandex robots.txt specifications.
