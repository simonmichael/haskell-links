<?php
function readLinks() {
  $recs = [];
  if (($h = fopen("../links.csv", "r")) !== FALSE) {
	fgetcsv($h);
	while (($r = fgetcsv($h)) !== FALSE) {
	  $recs[] = [$r[1], $r[0], $r[3], $r[2]];
	}
	fclose($h);
  }
  return $recs;
}
?>

<html>
  <head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready( function () {
  $('table#links').DataTable({
	// https://datatables.net/manual/options
	data: <?php echo json_encode(readLinks()) ?>,
	order: [[1,'asc']],
	fixedHeader: true,
	columns: [
	  {
		className: 'id',
	  },
	  {
		className: 'url',
		"render": function(data ) { return '<a href="'+data+'">'+data+'</a>'; },
	  },
	  {
		className: 'desc',
	  },
	  {
		className: 'tags',
	  },
	],
	paging: false,
	// pageLength: -1,
	// lengthMenu: [100,200,500,'All'],
  });
  $('input[type=search]').focus();
});
</script>

<link rel=stylesheet href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
<style>
body {
  font-family: sans-serif;
}
.dataTables_wrapper .dataTables_filter {
  float:left;
  text-align:left;
}
.odd td {
  background-color:#f8f8f8;
}
/* .even {

} */
table#links {
  width: 100%;
  max-width: 100%;
}
th {
  text-align: left;
}
td.id {
  font-size:small;
}
td.url {
  max-width:20em;
  overflow-x:hidden;
  white-space:nowrap;
  text-overflow: ellipsis;
}
/* td.desc {
  font-style:italic;
} */
td.tags {
  font-size:small;
  /* font-family:monospace; */
}
</style>
  </head>

  <body>
	<h1>Haskell Links Library</h1>

	<p>
	  Search my collected Haskell links: currently just
	  lambdabot's @where database
	  plus a few manually gathered links.
	  @where links can be viewed (<tt>@where ID</tt>) or updated (<tt>@where+ ID NEWTEXT</tt>)
	  in any Haskell IRC channel where lambdabot is present.
	  Read more about
	  <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>
	  and
	  <a href="https://github.com/simonmichael/haskell-links#some-principles">implementation</a>.
	  Would you like to
	  <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">help</a> ?
    Recent <a href="https://github.com/simonmichael/haskell-links/commits/main">changes</a>.
	  <!-- in <a href="https://github.com/simonmichael/haskell-links">the repo</a>. -->
	</p>

	<!-- https://datatables.net/examples/basic_init/index.html -->
	<table id="links">
		<thead>
		  <tr>
		  <th>ID</th>
		  <th>URL</th>
		  <th>DESCRIPTION</th>
		  <th>TAGS</th>
		</tr>
	  </thead>
	</table>

  </body>
</html>
