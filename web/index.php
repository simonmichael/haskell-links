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

// https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

$(document).ready( function () {
  // https://datatables.net/manual
  var table = $('table#links').DataTable({
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

  var search = $('#links_filter input[type=search]');

  function updateLocationFromSearch() {
    var url = new URL(window.location.href);
    var currentsearchterm = search.val();
    if (currentsearchterm)
      url.searchParams.set('q', currentsearchterm);
    else
      url.searchParams.delete('q');
    window.location = url;
  }

  $(search).on('keypress', function(e) {
    if (e.which == 13) updateLocationFromSearch();  // update location on enter
  });

  // problematic, also triggers on column sort
  // table.on('search.dt', function (e) {
  //   if (!search.val()) updateLocationFromSearch();   // update location on clearing the search
  // });
  
  if (params.q)
    table.search(params.q).draw();  // search for the q parameter if any
  else
    $('input[type=search]').focus();  // otherwise show all and await input

});
</script>

<link rel="icon" type="image/png" href="/HaskellLogoGrey.png">
<link rel=stylesheet href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
<style>
body {
  font-family: sans-serif;
  /* font-size:small; */
  /* padding: 1em 0; */
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
/* #about {
  font-size:small;
} */
#links_filter input[type=search] {
  margin-left:8px;
  width: 20em;
  font-size:medium;
  /* font-weight:bold; */
  /* font-style:italic; */
}
table#links {
  table-layout: fixed;
  width: 100%;
  max-width: 100%;
  /* font-size:small; */
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
  font-size:small;
}
/* td.desc {
} */
td.tags {
  font-size:small;
  /* font-family:monospace; */
}
</style>
  </head>

  <body>
  
	<h1><img src="HaskellLogoGrey.png" style="height:1em; position:relative; top:4px;" /> Haskell Links Library</h1>

	<p id="about">
	  A searchable collection of Haskell links, currently gathered
	  1. <a href="https://github.com/simonmichael/haskell-links/blob/main/in/manual.csv">manually</a>
	  2. <a href="https://github.com/simonmichael/lambdabot-where">from lambdabot</a>
    (accessible with <tt>@where ID</tt> or <tt>@where+ ID NEWTEXT</tt> in <a href="https://web.libera.chat/#haskell">#haskell</a>).
    Press enter for a permalink. Examples:
    <a href="?q=book">book</a>,
    <a href="?q=paper">paper</a>,
    <a href="?q=learn">learn</a>,
    <a href="?q=tutorial">tutorial</a>,
    <a href="?q=ghc">ghc</a>,
    <a href="?q=cabal">cabal</a>,
    <a href="?q=stack">stack</a>,
    <a href="?q=paste">paste</a>,
    <a href="?q=game">game</a>.
    Here's more about the
	  <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>
	  and
	  <a href="https://github.com/simonmichael/haskell-links#some-principles">implementation</a>;
	  would you like to
	  <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">help</a> ?
    <!-- Recent <a href="https://github.com/simonmichael/haskell-links/commits/main">changes</a>. -->
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
