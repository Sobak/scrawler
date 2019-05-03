# Matcher Block
Matcher blocks are always uses in context of _extracting_ something from the
webpage contents. They are responsible for providing content for either blocks
or objects (see [Getting started](../getting-started.md) for the definitions)
and therefore they are gouped into:

* `HTML matchers` - matchers returning single piece of information like div
  contents, paragraph or even a single word (for a block)
* `List matchers` - matcher returning a number of results which are then mapped
  to a list of objects. Obviously it may happen that there will be only one
  instance of given objects (like single article per page or some general data
  marked with `once()` method) but such objects still use list matchers for the
  sake of consistency
  
> **Note:** HTML matchers always look for content in scope of their current
> object exclusively. For example if you define an object for posts on some
> website, HTML matchers assigned to the `title` field will only look for
> some pattern inside single post that is currently processed.

For the usage examples please read the [Configuration](../configuration.md)
chapter or check the code sample on the main page.

## CssSelectorHtmlMatcher
This is one of the most basic HTML matchers which works in a manner very similar
to jQuery. Just pass the CSS selector specifying the element you are interested
in.

```php
->addFieldDefinition('title', new CssSelectorHtmlMatcher('h1.entry-title a'))
```

## CssSelectorListMatcher
List variant of the CSS selector matcher used to provide the objects.

```php
->addObjectDefinition('post', new CssSelectorListMatcher('article.hentry'), function (ObjectConfiguration $object) {
    // ...
})
```

## RegexHtmlMatcher
Matches HTML (or simple text without tags depending on the selection being made)
using regular expression. It **always** looks for the named group called `result`.

```php
->addFieldDefinition('age', new RegexHtmlMatcher('#I am (?P<result>\d+) years old?#m'))
```

## XpathHtmlMatcher
Matches HTML (or simple text) based on the XPath expression.

## XpathListMatcher
Matches list of elements for objects using the XPath expression.
