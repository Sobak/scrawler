# Cookbook: getting URLs from external source
Most of the examples shown in the course of this documentation assumes that the
list of subsequent URLs that Scrawler will follow is somehow obtained from the
page (e.g. by fetching all links to the same domain using [`SameDomainUrlListProvider`][same-domain])
or calculated dynamicly (with [`ArgumentAdvancerUrlListProvider`][argument-advancer])
ahead of time.

It may happen, though, that you already know which URLs exactly you need to visit
and you only want to make Scrawler process the URLs from the list. For that
scenario the perfect URL list provider to use would be the [`ArrayUrlListProvider`][array-list].

It's a simple URL list provider that only requires a list of URLs to be passed into it.
In this example we'll fetch them from a simple text file where every line is a single
URL to process. However, you only need an an array of strings so getting it from
the database, CSV file or even via some request to the remote service is completely
possible and doesn't change anything from Scrawler's standpoint.

The sample `*.txt` file:

```
http://example.com/user/john
http://example.com/user/alice
http://example.com/user/bob
http://example.com/user/jane

/user/richard
/user/andrew
```

Scrawler configuration file:

```php
<?php

declare(strict_types=1);

use App\UserEntity;
use Sobak\Scrawler\Block\ClientConfigurationProvider\ScrawlerUserAgentProvider;
use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\EntityPropertyFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\ArrayUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\DefaultConfigurationProvider;
use Sobak\Scrawler\Configuration\ObjectConfiguration;

require 'vendor/autoload.php';

$users = file('users.txt'); // Assumed name of the text file

$baseUrl = array_shift($users);

$scrawler = new Configuration();
$scrawler = (new DefaultConfigurationProvider())->setDefaultConfiguration($scrawler);

$scrawler
    ->setOperationName('example.com user scrapper')
    ->setBaseUrl($baseUrl)
    ->addClientConfigurationProvider(new ScrawlerUserAgentProvider('Scrawler Test', 'http://sobak.pl'))
    ->addUrlListProvider(new ArrayUrlListProvider($users))
    ->addObjectDefinition('user', new CssSelectorListMatcher('.userinfo'), function (ObjectConfiguration $object) {
        $object
            ->addFieldDefinition('username', new CssSelectorHtmlMatcher('h1'))
            ->addFieldDefinition('location', new CssSelectorHtmlMatcher('span.from'))
            ->addFieldDefinition('bio', new CssSelectorHtmlMatcher('#about > div'))
            ->addEntityMapping(UserEntity::class)
            ->addResultWriter(UserEntity::class, new JsonFileResultWriter([
                'directory' => 'users/',
                'filename' => new EntityPropertyFilenameProvider([
                    'property' => 'username',
                ]),
            ]))
        ;
    })
;

return $scrawler;
```

Since Scrawler's config is an object returned from within a regular PHP file we can
easily put our users fetching logic there. Please note that we use `array_shift()`
function to do so. The reason is that Scrawler always require base URL to start with
so we'll simply take first line of the file as the base and skip it for the URL provider
itself.

URLs passed to the `ArrayUrlListProvider` can be relative a long as there's an absolute
base URL they will be resolved against. We also don't need to worry about an empty line
at the end of the textfile as the URL provider will simply ignore it.

Remaining part of the configuration describes an object we are looking for, specifying
its fields and where to scrap them from. Every result will be mapped over the `User`
entity and written into its own JSON file named after the username. Entity class
for our case might look like so:

```php
<?php

namespace App;

class User
{
    protected $bio;

    protected $location;

    protected $username;

    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}
```

[argument-advancer]: ../blocks/urllistprovider.md#argumentadvancerurllistprovider
[array-list]: ../blocks/urllistprovider.md#arrayurllistprovider
[same-domain]: ../blocks/urllistprovider.md#samedomainurllistprovider
