DB=links.csv

import: \
	create \
	import-manual \
	import-where \
	sort

create:
	[[ -e $(DB) ]] || echo 'URL, ID, TAGS, "DESCRIPTION"' >$(DB)

import-manual:
	cat in/manual.csv | bin/import

import-where:
	cat in/where.tsv | bin/read-where | bin/import

sort:
	sort $(DB) >$(DB).tmp && mv $(DB).tmp $(DB) || rm -f $(DB).tmp

commit:
	@( git commit -m "update" -- $(DB) 2>&1 | grep -E '(^\[|file changed)' ) || echo "no changes"

update: import commit

redo:
	rm -f $(DB)
	make -s import

# publish to github only
p:
	@git push github

# forcibly
P:
	@git push -f github

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
