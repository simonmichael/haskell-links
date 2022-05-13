<?php
// * haskell-links.org/index.php

// ** PHP /////////////////////////////////////////////////////////////////////

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

// ** JS //////////////////////////////////////////////////////////////////////

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
  
  if (params.q) table.search(params.q).draw();  // search for the q parameter if any

  $('input[type=search]').focus();  // await input

});

</script>

<?php
// ** CSS /////////////////////////////////////////////////////////////////////
?>

<link rel="icon" type="image/png" href="/HaskellLogoGrey.png">
<link rel=stylesheet href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
<style>
body {
  background-color:#e0e0e0;
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
div#about {
  font-size:small;
}
#links_filter input[type=search] {
  margin-left:8px;
  width: 20em;
  font-size:medium;
  background-color: white;
  font-weight:bold;
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

<?php
// ** ABOUT ///////////////////////////////////////////////////////////////////
?>

  <body>

    <div id="about">
      <h1>
        <img src="HaskellLogoGrey.png" style="height:1em; position:relative; top:3px;" />
        <a href="https://haskell-links.org"
           style="text-decoration:none; color:black;">Haskell Links Library</a>
      </h1>
      <p>
        A collection of <a href="https://haskell.org">Haskell</a> links, gathered
        1. <a href="https://github.com/simonmichael/haskell-links/blob/main/in/manual.csv">manually</a>
        2. <a href="https://github.com/simonmichael/lambdabot-where">from lambdabot</a>
        (accessible with <tt>@where ID</tt> or <tt>@where+ ID NEWTEXT</tt> in <a href="https://web.libera.chat/#haskell">#haskell</a>).
        Here's more about the   <!-- help emacs highlighter: ' -->
        <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>
        and
        <a href="https://github.com/simonmichael/haskell-links#data">implementation</a>;
        would you like to
        <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">help</a> ?
        <!-- Recent <a href="https://github.com/simonmichael/haskell-links/commits/main">changes</a>. -->
        <br>
        <!-- Shift-click column headings for multi-sort. -->
        Press enter for a permalink.
        Example searches:
        <a href="?q=book">book</a>,
        <a href="?q=paper">paper</a>,
        <a href="?q=learn">learn</a>,
        <a href="?q=tutorial">tutorial</a>,
        <a href="?q=ghc">ghc</a>,
        <a href="?q=cabal">cabal</a>,
        <a href="?q=stack">stack</a>,
        <a href="?q=paste">paste</a>,
        <a href="?q=game">game</a>
        <br>
        And Haskell search tools:
        <a href="https://www.extrema.is/articles/haskell-books">Books</a> |
        <a href="https://www.haskell.org/documentation">Official docs list</a> |
        <a href="https://wiki.haskell.org/Special:RecentChanges">Wiki changes</a> |
        <a href="https://wiki.haskell.org/index.php?title=Special:AllPages">Wiki pages</a> |
        <a href="https://github.com/Gabriella439/post-rfc/blob/main/sotu.md#state-of-the-haskell-ecosystem">State of the Haskell ecosystem</a> |
        <a href="http://dev.stephendiehl.com/hask">What I Wish I Knew When Learning Haskell</a> |
		<a href="https://haskell.pl-a.net">Discussion feeds</a> |
        <a href="https://discourse.haskell.org">Discourse</a> |
        <a href="https://www.reddit.com/r/haskell/new">Reddit</a> |
        <a href="https://www.haskell.org/mailing-lists/">Mail lists</a> |
        <a href="https://www.haskell.org/irc/">IRC channels</a> |
        <a href="https://view.matrix.org/?query=haskell">Matrix rooms</a> |
        <a href="https://stackoverflow.com/questions/tagged/haskell">Stack Overflow</a> |
        <a href="https://haskell.foundation/podcast">HF podcast</a> |
        <a href="https://hackage.haskell.org/packages/browse">Hackage</a> |
        <a href="https://packdeps.haskellers.com">Hackage deps</a> |
        <a href="https://hoogle.haskell.org">Hoogle</a> |
        <a href="https://www.stackage.org/lts">Stackage LTS</a> |
        <a href="https://www.stackage.org/nightly">Stackage Nightly</a> |
        <a href="https://github.com/search/advanced">Github</a> |
        <a href="https://github.com/search?o=desc&q=language%3AHaskell+stars%3A%3E=100&ref=searchresults&s=stars&type=Repositories">Github top starred</a> |
        <a href="https://gitlab.com/explore/projects/topics/haskell">Gitlab</a> |
        <a href="https://gitlab.haskell.org/?sort=stars_desc">GHC/libs gitlab</a> |
        <a href="https://github.com/ghc-proposals/ghc-proposals#readme">GHC proposals</a> |
        <a href="https://github.com/haskell/core-libraries-committee#readme">Core libs proposals</a> |
        <a href="https://github.com/haskellfoundation/tech-proposals#readme">HF tech proposals</a> |
        <a href="https://github.com/simonmichael/haskell-links#related-projects--link-sources">More lists</a>
  <!--
        <a href=""></a> |
        <a href=""></a> |
  -->
      </p>
    </div>

<?php
// ** TABLE ///////////////////////////////////////////////////////////////////
?>

    <table id="links">
        <thead>
          <tr>
          <th>ID</th>
          <th>URL</th>
          <th>Description</th>
          <th>Tags</th>
        </tr>
      </thead>
    </table>

  </body>
</html>
