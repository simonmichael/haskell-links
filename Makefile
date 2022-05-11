DB=links.csv

import: \
	create \
	import-where \
	sort

create:
	[[ -e $(DB) ]] || touch $(DB)

import-where:
	cat in/where.tsv | bin/read-where | bin/import

sort:
	sort $(DB) > $(DB).tmp && mv $(DB).tmp $(DB) || rm -f $(DB).tmp

