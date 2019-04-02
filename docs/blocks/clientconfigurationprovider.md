# ClientConfigurationProvider Block
Client configuration providers are data transfer objects responsible
for keeping additional configuration of Scrawler's underlying Guzzle
instance.

Every class must implement `getConfiguration()` and `setConfiguration()`
methods which can be inherited from the available abstract class. There
is no additional abstraction provided over Guzzle's configuration format
which means that stored arrays must adhere to format required by the
[documentation of Guzzle][guzzle-docs] itself.

Configuration arrays from registered providers are recursively merged
in order the providers were registered in.

Client configuration providers are registered as usual, by providing
an object of class implementing the valid interface and can be removed
by using a fully qualified class name of the provider (`::class` value).

## BasicAuthProvider
This client configuration provider enables you to send HTTP Basic Auth
credentials required by the website. You need to provide username and
password as arguments to the class constructor.

```php
->addClientConfigurationProvider(new BasicAuthProvider('username', 'password'))
```

## ScrawlerUserAgentProvider
This is a default client configuration provider which includes Scrawler
name and version inside the user agent. It does, however, require you to
pass information about your specific bot based on Scrawler so that owners
of websites you crawl can actually identify and contact you.

[guzzle-docs]: http://docs.guzzlephp.org/en/stable/request-options.html
