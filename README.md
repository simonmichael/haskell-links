# haskell-links

> *There's no way these very frequent reddit and chat requests for learning materials can capture all the existing resources. 
There are too many, and folks who know where they are get burned out reposting them. 
There have been many attempts to gather them, haskell.org/documentation being the most obvious, 
but none of them have fully succeeded. We could really do with some more systematic, scalable (crowd-sourced, lightweight) approach.*
([discussion](https://www.reddit.com/r/haskell/comments/um43bz/most_current_materials_for_learning_haskell/i80f40x/))

Here is one more small attempt. 
Right now it's just me, myself and I.

## Goals

- A comprehensive, up to date, maintained collection of links to Haskell resources (docs, sites, services..),
- that is scalable, durable, cost-effective and community-owned
- searchable and with the best content emphasised (eg by voting)
- which all kinds of Haskellers find useful and time-saving
- and which accelerates Haskell learning and adoption.

## How will we recognise success ?

- Questions like "What's a good Haskell book?" and "Please recommend Haskell learning materials"
  can be answered easily and efficiently with a high quality link. 
- Knowledge of all available resources is more widely shared across the community,
  and easily discoverable by newcomers.
- High quality content is not forgotten or perpetually undiscovered.
- The community's most popular resources are featured prominently.
- Maintenance is easy and enjoyable.

## Some principles

- Fun to use, contribute and manage.
- Avoid unnecessary software dev/ops work - this aims to fill a need, not to generate work or a hobby.
  Existing, good enough, cheap beats pending, perfect, costly.
- Ruthless efficiency - think Craigslist. Less is more.
- Chat ops, at some point - think lambdabot's @where.
- Good web presence and SEO.
- High [bus factor](https://en.wikipedia.org/wiki/Bus_factor), low [toil](https://sre.google/sre-book/eliminating-toil).
- Solo or community operated, but always ultimately community-owned.

## Data

The primary data to be gathered is a link, consisting of

- the URL. The unique primary key.
- an optional short id, useful for chat ops. This too should be unique.
- optional tags, a spaced list of possibly-hyphenated lowercase words, used for categorisation
- an optional description. Plain text or perhaps link-free markdown.

These will be public data, stored in a single CSV file in this git repository,
for durability, manageability and maximum readability/tool compatibility.
The CSV uses lazy quoting - values are double-quoted only if needed (eg if a comma must be used in the description).

Later, secondary data may be added to enrich the above, particularly votes.
This will likely be semiprivate data, stored in a sqlite DB by a web app, 
backed up and accessible to current/future admins and perhaps the Haskell Foundation 
for durability.

## UI

- The first UI will be viewing/editing the CSV file via git/github.
- Later there should be a web app providing filtering/sorting/voting/permalinks/feeds/discoverability/management.
- And a chat bot interface running in #haskell and/or wherever it's wanted.

## Web presence

Initially a custom domain, https://haskell-links.org, redirecting to this repo.

## Capture

Links are to be collected by some combination of:

- manual edits to the master CSV file
- scripts/cron jobs, also in this repo, collecting links from existing sources:
  haskell.org, planet.haskell.org, /r/haskell, etc.
- the web UI
- the chat UI

There is an import/reconcile process which adds/updates existing data 
without creating duplicates or conflicts.

## Timeline / Roadmap

- 2022-05-10 project start
- gather some link sources, develop some import scripts/procedures
- start gathering links
- a simple web UI
- a simple chat UI

## Related projects / lists

- https://github.com/simonmichael/lambdabot-where archive of lambdabot's @where db
- https://www.extrema.is/articles/haskell-books filterable haskell book list
- https://guide.aelve.com retired haskell docs/links wiki-db
- https://haskell.org/documentation official starting point
- https://planet.haskell.org many (not all) haskell blog posts pass through here
- https://reddit.com/r/haskell or here
- https://github.com/cohomolo-gy/haskell-resources foundational haskell papers
- http://dev.stephendiehl.com/hask epic practical haskell reference tome
- https://www.fpcomplete.com/haskell/learn good practical tutorials and library overviews
- https://serokell.io/blog/haskell good introductory and general posts
- http://www.vex.net/~trebla/haskell/learn-sources.html reviews of some classic docs
- https://en.wikibooks.org/wiki/Haskell a well-written textbook
- https://github.com/krispo/awesome-haskell
- https://github.com/uhub/awesome-haskell
