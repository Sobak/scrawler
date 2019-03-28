# Entities
When Scrawler parses the website its ultimate goal is to map scrapped information
to entities and then to write it. This documentation chapter focuses on entities
aspect, their mapping details and some general tips on how to write them.

Entities are plain PHP objects, they don't have to implement any interface or extend
from any base class. Their primary responsibility is to store data fetched by the
Scrawler so that it can be then used by result writers. That's why typically entities
do not contain much logic. Most of what they do is defining properties (most recommended
are private or protected) along with getters and setters so like a Data Transfer Object.

```php
<?php

class Post
{
    protected $content;

    protected $date;

    protected $title;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
}
```

> **Note:** You can use PHP [argument][php-argument-type] and [return][php-return-type]
> type declarations to enforce type correctness.

When writing data to the entity Scrawler uses your setters to hydrate an object
and set values for underlying properties. This process is called _entity mapping_.
It means that if you define property without a setter its value _will not_ be set.
Instead it will be silently ignored and the value will be null even if you define
the getter. This is by design as Scrawler does not require you to map every field
of object definition to the entity field. Note that this is true even if you make
property public.

Similar process happens inside result writers when they read from entities. If some
property doesn't define its getter the value will not be taken into the account.
In this case, however, that may cause a failure if your result writer configuration
makes that field required (e.g. by using database result writer with non-nullable
column).

## Entity mapping
Your entities will be written and read by the Scrawler internally. It means that
whole entity mapping logic have been outlined above and you have no influence
over it (e.g. there is no `toArray()` method or similar). You can, however, use
getters and setters to achieve custom mapping. For example if the underlying
result writer expects post date to be a PHP `DateTime` class object instead of
pure string representation scrapped from the website you could call `DateTime`
constructor in your setter and use the argument.

You can even create a kind of _virtual fields_ for your entity which will not be
read from the website directly but computed from other available information.
Following example above if you would like to store a slug for a post and have
it generated from the title you can add one more property with getter.

```php
protected $slug;

// ...

public function getSlug()
{
    return str_replace(' ', '-', $this->title);
}
```

Setter does not really make sense in this case as we assume you will not be providing
definition for the `slug` field in your configuration. Result writer will see the `slug`
property in your entity and attempt to use it, though.

Finally, your entities may sometimes need additional mapping metadata. This is a case
e.g. when using database result writer. The Doctrine library which is used under the
hood will expect each property to be described by annotation so that proper database
column can be used for it. Then your post entity may look like so:

```php
<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\Mapping as ORM;
use Sobak\Scrawler\Support\Utils;

/**
 * @ORM\Entity()
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="string")
     */
    protected $date;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->slug = Utils::slugify($title);
        $this->title = $title;
    }
}
```

[php-argument-type]: https://www.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration
[php-return-type]: https://www.php.net/manual/en/functions.returning-values.php#functions.returning-values.type-declaration
