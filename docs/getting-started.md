# Getting started
Scrawler is a scriptable combination of crawler and scrapper which aims to provide
easy solution for going to any webpage, scrapping its content, parsing it and then
storing the way you want it — all accordingly to the rules you provide.

> **Note:** for the real-life example of code please check the main page of the
> documentation.

Scrawler follows a declarative software paradigm, meaning most of its behavior is
driven by the provided configuration. When running `scrawler` commandline script
(being default Scrawler's entrypoint) you need to tell it where your configuration
file lives. This file must return the `Configuration` object. More details on it
will be discussed in next chapter.

## Basic flow
Each operation run by Scrawler consists of few fundamental steps:

- visiting the URL
- scrapping information from the page
- mapping this information into the entities you define
- writing entities down
- looking for next URLs…

## Objects
Object in Scrawler is a most basic unit of what you will scrap from the website.
Each object must consist of fields like post may consists of content and title.
Of course this is just an example. You will need to determine the patterns for
data you are interested with on particular website.

In most cases there will be multiple instances of given object find on a website
(e.g. one per page or even multiple on every page). It may also happen, though,
that some pieces of information you want to get will be global for the whole website
but repeated on every subpage (e.g. its header and description). Just to clarify,
you will still need to describe them as an object, even if you are interested in
just single occurence. You can hovewer mark such objects to be fetched only once.

## Entity mapping
Once you have defined the fields your object of information have, you will need
to provide an entity name you want to map it to. Entities are PHP objects that
keep and map scrapped results. In many cases they are the only thing that you
will need to write yourself while using builtin classes for everything else.
Entities are covered in detail in [their own chapter](entities.md).

## Result writers
Now that you have defined patterns for the information you are interested in, you
have mapped that information to PHP objects, it's probably time to finally do
something useful with that information. That's where result writers come in. There
are many types of result writers available depending where you want to store
the data in text file, a database, JSON or even simply dump it on a screen.
Result writers have been described in detail within a 
[dedicated chapter](blocks/resultwriter.md).

## Blocks
Aside from aforementioned main steps Scrawler needs to perform there is a couple
of other bits that need to be accounted for when scrapping a website. For example
looking for next avaiable URLs, setting up the HTTP client in a proper way, writing
down logs of an operation etc.

Each one of those (and result writers, too!) is called a _block_ in Scrawler. By
providing a bunch of builtin block implementations you can easily swap parts of
logic that Scrawler will execute to fulfill above requirements. Of course you can
create your own implementations for any of blocks by simply following an interface.
That's where real power of Scrawler is, you can freely use your entirerly custom
code if the default solutions are not sufficient. For the list of all available
block types and their implementations please check [this](blocks.md) chapter of
the documentation.
