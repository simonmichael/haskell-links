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
	@( git commit -qm "update" -- $(DB) | grep -E '(^\[|file changed)' ) || echo "no changes"

update: import commit

redo:
	rm -f $(DB)
	make -s import

# push to github only, optionally with -f
publish%:
	git push $* github

# push to production only, optionally with -f
deploy%:
	git push $* joyful
	ssh joyful.com 'cd src/haskell-links && git reset --hard && git fetch github'

# normal push to github and production
pubdep:
	git push github
	ssh joyful.com "cd src/haskell-links && git pull github"

# forced push to github and production
pubdep-f: publish-f deploy-f

