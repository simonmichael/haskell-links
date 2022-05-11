DB=links.csv

import: \
	create \
	import-where \
	sort

redo:
	rm -f $(DB)
	make -s import

create:
	[[ -e $(DB) ]] || echo 'URL, ID, TAGS, "DESCRIPTION"' >$(DB)

import-where:
	cat in/where.tsv | bin/read-where | bin/import

sort:
	sort $(DB) >$(DB).tmp && mv $(DB).tmp $(DB) || rm -f $(DB).tmp

