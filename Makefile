DB=links.csv

import: \
	create \
	import-lambdabot \
	sort

create:
	[ -e $(DB) ] || echo 'URL, ID, TAGS, "DESCRIPTION"' >$(DB)

# unused
# import-manual:
# 	cat in/manual.csv | bin/import

# import LB's @where db. If a url appears multiple times, the one with alphabetically-first id wins.
import-lambdabot: in/where.tsv
	cat in/where.tsv | bin/read-where | bin/import

# download and convert LB's @where db. https://wiki.haskell.org/IRC_channel#lambdabot
.PHONY: in/where.tsv
in/where.tsv:
	curl -s  http://silicon.int-e.eu/lambdabot/State/where \
	 | zcat | iconv -c $< | paste -s -d '\t\n' - >$@

# ensure case-sensitive sort
SORT:=LC_COLLATE=C sort

sort:
	$(SORT) $(DB) >$(DB).tmp && mv $(DB).tmp $(DB) || rm -f $(DB).tmp

regen:
	rm -f $(DB)
	make import

commit:
	@( git commit -m "update" -- $(DB) 2>&1 | grep -E '(^\[|file changed)' ) || echo "no changes"

#update: import commit   # does not see changes/removals
update: regen commit   # more destructive, check make regen first

# publish to github only
p:
	@git push github

# forcibly
P:
	@git push -f github

# XXX these could interfere with hourly db-updating cron job on the server - try to avoid deploying on the hour

# deploy to production only
d:
	@git push joyful
	@ssh joyful.com 'cd src/haskell-links && git reset --hard && git fetch github'

# forcibly
D:
	@git push -f joyful
	@ssh joyful.com 'cd src/haskell-links && git reset --hard && git fetch github'

# publish and deploy
pd:
	@git push github
	@ssh joyful.com "cd src/haskell-links && git pull github"
	@git fetch joyful

# forcibly
PD: P D
