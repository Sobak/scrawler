# Cookbook: generating object fields dynamically
From time to time you may want to perform some operations on the scrapped results
which are simple enough to do them using Scrawler itself and do not need to involve
entirerly custom result writer (which would, in turn, lead to code repetition, to some
point).

> **Note:** example config shown in this chapter is based on the [previous case][multiple].
> Please read it troughtfully to get the context and code we'll start with.

For such cases you might want to utilize the knowledge [how the entities are mapped][entities]
in a clever way. As the dedicated chapter states, result writers are looking at
all defined getters to obtain collection of fields representing particular result.

While in most cases you want your getter to only retrieve the value of the underlying
property, you are not limited to that. You can return any data in a format that
result writer will understand based on whatever criteria you want, e.g. computing
it from other entity fields.

To showcase that possiblity we will assume that we would like to improve the results
from blog scrapper example described in the previous chapter by _linking_ post metadata
stored in the CSV file to the respective HTML file keeping post content.

In order to do so we will define `getLink()` method inside the entity which
will in turn result in `link` column being added to the CSV. As for its logic we
intend to somehow match the incremental counter of content's filename provider.
For the sake of this tutorial let's assume quite dumb code like this:

```php
<?php

declare(strict_types=1);

namespace App;

class PostMetaEntity
{
    protected static $counter = 0;

    // ...

    public function getLink()
    {
        return sprintf('posts/%d.html', ++self::$counter);
    }

    // ...
}
```

As you can see we can actually place any logic we want inside the getter. That
also includes computing field value using other fields – you can get their
properties as all setters are already called at that point.

That way you can provide somehow _virtual_ fields for the entities representing
your results.

[entities]: ../entities.md
[entity-filename]: ../blocks/filenameprovider.md#entitypropertyfilenameprovider
[multiple]: multiple-result-writers.md
