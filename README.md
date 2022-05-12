# haskell-links

I [wrote](https://www.reddit.com/r/haskell/comments/um43bz/most_current_materials_for_learning_haskell/i80f40x/):
> *There's no way these very frequent reddit and chat requests for learning materials can capture all the existing resources. 
There are too many, and folks who know where they are get burned out reposting them. 
There have been many attempts to gather them, haskell.org/documentation being the most obvious, 
but none of them have fully succeeded. We could really do with some more systematic, scalable (crowd-sourced, lightweight) approach.*

Here comes another attempt! https://haskell-links.org is the web UI.

Right now it's just me, myself and I and this is just getting started.

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

### Official-ish

- https://haskell.org/documentation                           official starting point
- https://planet.haskell.org                                  many haskell blog posts pass through here
- https://reddit.com/r/haskell                                or here
- https://hackage.haskell.org/packages/browse                 hackage package search                (#hackage)
- https://packdeps.haskellers.com                             hackage dependency monitor / reverse deps
- https://hoogle.haskell.org                                  hackage package API search
- https://www.stackage.org/lts                                stackage LTS package/API search
- https://www.haskell.org/mailing-lists                       mail lists
- https://discourse.haskell.org                               discussions forum
- https://stackoverflow.com/questions/tagged/haskell          haskell questions on Stack Overflow
- https://www.haskell.org/irc                                 some IRC channels
- https://view.matrix.org/?query=haskell                      some Matrix rooms
- https://wiki.haskell.org/                                   haskell wiki
- https://en.wikibooks.org/wiki/Haskell                       haskell wikibook
- https://wiki.haskell.org/Language_and_library_specification  language specs

### Link databases

- https://github.com/simonmichael/lambdabot-where             archive of lambdabot's @where db			(int-e)
- https://www.extrema.is/articles/haskell-books               filterable haskell book list				(tcard, Travis Cardwell)

### Link lists

- https://wiki.haskell.org/Learning_Haskell                   
- http://jackkelly.name/wiki/haskell.html                     annotated links							(Jack Kelly)
- http://www.vex.net/~trebla/haskell/learn-sources.html       reviews of some classic docs				(monochrom, Albert Y.C. Lai)
- https://github.com/cohomolo-gy/haskell-resources            A List of Foundational Haskell Papers		(@cohomolo-gy)
- https://github.com/krispo/awesome-haskell                   Awesome Haskell							(@krispo, Konstantin Skipor)
- https://github.com/uhub/awesome-haskell                     awesome-haskell							(@uhub)

### Link-heavy docs

- http://dev.stephendiehl.com/hask                            What I Wish I Knew When Learning Haskell	(Stephen Diehl)
- https://github.com/Gabriella439/post-rfc/blob/main/sotu.md  State of the Haskell ecosystem			(@Gabriella439, Gabriella Gonzalez)
- https://wiki.haskell.org/Typeclassopedia                    reference for the standard type classes   (Brent Yorgey)

### Doc collections

- https://guide.aelve.com                                     retired haskell docs/links wiki-db		(peargreen, Artyom Kazak)
- https://www.fpcomplete.com/haskell/learn                    good practical tutorials and library overviews
- https://www.schoolofhaskell.com                             more FP Complete docs
- https://serokell.io/blog/haskell                            good introductory and general posts

### Other

- https://www.fosskers.ca/en/blog/base                        GHC/base/etc. compatibility
- https://gitlab.haskell.org/ghc/ghc/-/wikis/commentary/libraries/version-history  GHC/boot library versions
- https://github.com/search/advanced                          search for haskell repos on github


## Discuss / contribute

- Chat `sm` in #haskell, via
  <a href="https://web.libera.chat/#haskell">Libera</a> or
  <a href="https://matrix.to/#/#haskell:libera.chat">Matrix</a>.
- PRs fixing bugs are especially welcome.
- More link sources are needed.
