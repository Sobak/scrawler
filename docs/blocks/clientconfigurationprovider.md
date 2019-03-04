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

## ScrawlerUserAgentProvider
This is a default client configuration provider. It sets an user agent
to Scrawler with the addition of its current version, current PHP version
and Scrawler's website.

[guzzle-docs]: http://docs.guzzlephp.org/en/stable/request-options.html
