DB=links.csv

import: \
	create \
	import-where \
	sort

create:
	[[ -e $(DB) ]] || echo 'URL, ID, TAGS, "DESCRIPTION"' >$(DB)

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

