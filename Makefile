DB=links.csv

import: \
	import-where \
	sort

import-where:
	cat in/where.tsv | bin/read-where | bin/import

sort:
	sort $(DB) > $(DB).tmp && mv $(DB).tmp $(DB)

