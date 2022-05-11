# haskell-links

I [wrote](https://www.reddit.com/r/haskell/comments/um43bz/most_current_materials_for_learning_haskell/i80f40x/):
> *There's no way these very frequent reddit and chat requests for learning materials can capture all the existing resources. 
There are too many, and folks who know where they are get burned out reposting them. 
There have been many attempts to gather them, haskell.org/documentation being the most obvious, 
but none of them have fully succeeded. We could really do with some more systematic, scalable (crowd-sourced, lightweight) approach.*

Here comes another attempt! Right now it's just me, myself and I and this is just getting started.

## Goals

- A comprehensive, up to date, maintained collection of links to Haskell resources (docs, sites, services..),
- that is scalable, durable, cost-effective and community-owned
- searchable and with the best content emphasised (eg by voting)
- which all kinds of Haskellers find useful, time-saving, and enjoyable
- and which accelerates Haskell learning and adoption.

## How will we recognise success ?

- Questions like "What's a good Haskell book?" and "Please recommend Haskell learning materials"
  can be answered easily and efficiently with a high quality link. 
- Knowledge of all available resources is more widely shared across the community,
  and easily discoverable by newcomers.
- High quality content is not forgotten or perpetually undiscovered.
- The community's most popular resources are featured prominently.
- Using, contributing to, and managing the service is easy and fun.

## Some principles

- Avoid unnecessary software dev/ops work - this aims to fill a need, not to generate work or a hobby.
  Existing, good enough, cheap beats pending, perfect, costly.
- Ruthless efficiency - think Craigslist. Less is more.
- High reliability.
- Chat ops, at some point - think lambdabot's @where.
- Good web presence and SEO.
- High [bus factor](https://en.wikipedia.org/wiki/Bus_factor), low [toil](https://sre.google/sre-book/eliminating-toil).
- Can be solo or community operated, but always ultimately community-owned.

## Data

The primary data to be gathered is a link record, consisting of

- the URL. The unique primary key.
- an optional short id, a unique possibly-hypenated word, useful for chat ops
- optional tags, a spaced list of possibly-hyphenated lowercase words, used for categorisation
- an optional description. Plain text or perhaps link-free markdown, always enclosed in double quotes.
  (The other fields don't need quotes.) This and the other fields are always UTF8-encoded. 

These link records will be public data, stored in a single CSV file in this git repository,
for durability, manageability and maximum readability/compatibility.

Later, secondary data may be added to enrich the above, particularly votes.
This will likely be semiprivate data, stored in a sqlite DB by a web app, 
backed up and accessible to current/future admins and perhaps the Haskell Foundation 
for durability.

## Tags

All records have one tag indicating their source, such as:\
`manual`\
`where`

## Capture

Links are to be collected by some combination of:

- manual edits to the CSV
- scripts/cron jobs, also in this repo, collecting links from existing sources:
  haskell.org, planet.haskell.org, /r/haskell, etc.
- the web UI
- the chat UI

There should always be an import/reconcile procedure which adds/updates 
without creating duplicates or conflicts.

## UI

1. The CSV can be edited manually via git/github.
2. A web app will provide filtering, sorting, updating, voting, permalinks, feeds, discoverability.
3. A chat bot running in #haskell or wherever it's wanted will allow searching and updating.

## Web presence

A custom domain for now: http://haskell-links.org , serving the web UI.
Currently trying the slightly stickier title "Haskell Links Library".

## Timeline / roadmap

- [x] 2022-05-10 project start
- [x] gather some link sources
- [x] develop some import scripts/procedures
- [x] start gathering links
- [x] set up a simple web UI
- set up a simple chat UI

## Related projects / link sources

- https://github.com/simonmichael/lambdabot-where archive of lambdabot's @where db (int-e)
- https://www.extrema.is/articles/haskell-books filterable haskell book list (tcard, Travis Cardwell)
- https://guide.aelve.com retired haskell docs/links wiki-db (peargreen, Artyom Kazak)
- https://haskell.org/documentation official starting point
- https://planet.haskell.org many (not all) haskell blog posts pass through here
- https://reddit.com/r/haskell or here
- https://github.com/cohomolo-gy/haskell-resources foundational haskell papers
- http://dev.stephendiehl.com/hask epic practical haskell reference tome
- https://www.fpcomplete.com/haskell/learn good practical tutorials and library overviews
- https://serokell.io/blog/haskell good introductory and general posts
- http://www.vex.net/~trebla/haskell/learn-sources.html reviews of some classic docs (monochrom)
- https://en.wikibooks.org/wiki/Haskell a well-written textbook
- https://github.com/krispo/awesome-haskell (@krispo, Konstantin Skipor)
- https://github.com/uhub/awesome-haskell (@uhub)
- http://jackkelly.name/wiki/haskell.html

## Discuss / contribute

- Chat `sm` in #haskell, via
  <a href="https://web.libera.chat/#haskell">Libera</a> or
  <a href="https://matrix.to/#/#haskell:libera.chat">Matrix</a>
øø