# haskell-links

I [wrote](https://www.reddit.com/r/haskell/comments/um43bz/most_current_materials_for_learning_haskell/i80f40x/):
> *There's no way these very frequent reddit and chat requests for learning materials can capture all the existing resources. 
There are too many, and folks who know where they are get burned out reposting them. 
There have been many attempts to gather them, haskell.org/documentation being the most obvious, 
but none of them have fully succeeded. We could really do with some more systematic, scalable (crowd-sourced, lightweight) approach.*

Here comes another attempt! 

https://haskell-links.org, AKA the **Haskell Links Library**, is the web UI.

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

- Avoid unnecessary work - this aims to fill a need, not to generate work or a hobby.
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
- an optional (?) short id, a unique possibly-hypenated word, useful for chat ops
- an optional description. Plain text or perhaps link-free markdown, always enclosed in double quotes.
  (The other fields don't need quotes.) This and the other fields are always UTF8-encoded. 
- optional tags, a spaced list of possibly-hyphenated lowercase words, used for categorisation

These link records will be public data, stored in a single CSV file in this git repository,
for durability, manageability and maximum readability/compatibility.

Later, secondary data may be added to enrich the above, particularly votes.
This will likely be semiprivate data, stored in a sqlite DB by a web app, 
backed up and accessible to current/future admins and perhaps the Haskell Foundation 
for durability.

## Capture

Links are to be collected by some combination of:

- manual edits to the CSV
- scripts/cron jobs, also in this repo, collecting links from existing sources:
  haskell.org, planet.haskell.org, /r/haskell, etc.
- the web UI
- the chat UI

There should always be an import/reconcile procedure which adds/updates 
without creating duplicates or conflicts.

## Tags

All records have one tag indicating their source, such as `lambdabot`

## UI

1. CSV can be edited manually via git/github (?)
2. A web app will provide filtering, sorting, updating, voting, permalinks, appropriate feeds, discoverability.
3. A chat bot running in #haskell or wherever it's wanted will allow searching and updating.

## Web presence

The web app runs at https://haskell-links.org, a custom domain for now.

SEO is checked with [google search console](https://search.google.com/search-console?resource_id=sc-domain%3Ahaskell-links.org).

## Timeline

- 2022-05-10 project start
- gather some link sources
- develop some import scripts/procedures
- start gathering links
- set up a simple web view
- set up a simple chat ui
- 2022-05-16 version 1 announced
- v1.1, ui/link updates
- 2022-05-20 v1.2, new data scheme and repo, column filters enabled by default, gray background
- 2022-05-21 v1.3, works without javascript; supports multiple data sources, multiple tags, movable columns
- drop tagging goal for now, just track links' source
- 2025-04 intermittent site hangs started
- 2025-05-26 added cron job to restart it each quarter hour
- 2025-05-27 simplified ui, moved intro links elsewhere, dropped ugly popping css/js

More detail: [app changes](https://github.com/simonmichael/haskell-links/commits/main), [data changes](https://github.com/simonmichael/haskell-links-data/commits/main)

## Discuss / contribute

- Chat `sm` in #haskell, via
  <a href="https://web.libera.chat/#haskell">Libera</a> or
  <a href="https://matrix.to/#/#haskell:libera.chat">Matrix</a>.
  Discourse also works well, eg [here](https://discourse.haskell.org/t/ann-haskell-links-org-searchable-links-database).
- PRs fixing bugs are especially welcome.
- More link sources should be imported / interoperated with. (?)
- Enhance lambdabot or use another bot ?
- Some workflow permitting web edits, and ideally multiple edit UIs, is desirable.
- Where and how is master data stored ?
- Which other data sources are synced and how ?
- How are tags stored/edited ?
- How are votes tracked/stored/edited ?

## Related projects / potential link sources

### Official-ish

- https://haskell.org/documentation                           official starting point
- https://wiki.haskell.org                                    haskell wiki
- https://wiki.haskell.org/Special:RecentChanges              haskell wiki recent changes
- https://wiki.haskell.org/index.php?title=Special:AllPages   haskell wiki all pages
- https://en.wikibooks.org/wiki/Haskell                       haskell wikibook
- https://haskell.pl-a.net                                    aggregates most discussion sites/feeds
- https://planet.haskell.org                                  many haskell blog posts pass through here
- https://reddit.com/r/haskell                                or here
- https://www.haskell.org/mailing-lists                       mail lists
- https://discourse.haskell.org                               discussions forum
- https://stackoverflow.com/questions/tagged/haskell          haskell questions on Stack Overflow
- https://www.haskell.org/irc                                 some IRC channels
- https://view.matrix.org/?query=haskell                      some Matrix rooms
- https://haskell.foundation/podcast                          HF podcast
- https://hackage.haskell.org/packages/browse                 hackage package search                (#hackage)
- https://packdeps.haskellers.com                             hackage dependency monitor / reverse deps
- https://hoogle.haskell.org                                  hackage package API search
- https://www.stackage.org/lts                                stackage LTS package/API search
- https://www.stackage.org/nightly                            stackage Nightly package/API search
- https://wiki.haskell.org/Language_and_library_specification  language specs
- https://github.com/ghc-proposals/ghc-proposals               ghc proposals
- https://github.com/haskell/core-libraries-committee          core libraries proposals
- https://github.com/haskellfoundation/tech-proposals          HF tech proposals

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
- https://news.ycombinator.com/item?id=31459103               2022 discussion of tagging
