<?php // echo '<small><pre>'; var_export($_SERVER); echo '</pre></small>'; ?>
<?php
// * haskell-links.org/index.php
// ** PHP *****************************************************************

// router
$uri = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
switch ($uri) {
  case '' :
  case '/' :
    index();
    break;

  // case '/clickme' :
  //   echo '<a href="#" hx-post="/clicked" hx-swap="outerHTML">Clicked!</a>';
  //   break;
  // case '/clicked' :
  //   echo '<a href="#" hx-post="/clickme" hx-swap="outerHTML">Click me!</a>';
  //   break;

  default:
    http_response_code(404);
    echo "<h1>Not found</h1>";
    // require __DIR__ . '404.php';
    break;
}

// links db
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

function index() {

// ** HEAD *****************************************************************
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Haskell Links Library</title>
  <!-- <meta name="description" content=""> -->
  <!-- <meta name="author" content=""> -->

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="HaskellLogoGrey.png">

<?php
// ** CSS *****************************************************************
?>
  <!-- Font
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css"> -->

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<!--
-->
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="stylesheet" href="css/datatables.min.css"/>
  <style>

/* undo some skeleton css */
pre, blockquote, dl, figure, table, p, ul, ol, form, input, textarea, select, fieldset { margin-bottom: revert; }
a { color: revert; }
body { line-height: revert; }
h1 {
  font-size:3rem;
  font-weight:bold;
  margin-bottom:1rem;
}

/* modify some datatables css */
.dtsp-disabledButton {
  color: #aaa !important;   // #7c7c7c
}

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
.even {
  background-color:white !important;
}
#about {
  font-size:small;
  padding:1em 1em 0;
}
#links_filter, #links_info, .dtsp-panesContainer .dtsp-title {
  margin-left: 1em;
}
#links_filter input[type=search] {
  margin-left:8px;
  width: 20em;
  font-size:medium;
  background-color: white;
  font-weight:bold;
  /* font-style:italic; */
}
#links_filter > label::after {
  content: "(for a permalink, press enter)";
  margin-left:1em;
  font-size:small;
  font-weight:normal;
  font-style:italic;
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

<?php
// ** JS *****************************************************************
?>
  <!-- JS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script src="js/datatables.min.js"></script>
  <!-- <script src="js/htmx.min.js" defer></script> -->
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
    bAutoWidth: true,  // true adjusts column widths, false avoids table width change when empty
    order: [[1,'asc']],
    fixedHeader: true,
    searchPanes:{
      initCollapsed: true,
      threshold: 1,
    },
    // dom: 'Plfrtip',
    paging: false,
    // pageLength: -1,
    // lengthMenu: [100,200,500,'All'],
  });

  var search = $('#links_filter input[type=search]');
  // search.attr('title','press enter for a permalink');

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

</head>
<?php
// ** BODY *****************************************************************
?>
<body>
<!-- <div class="container"> <!-- not full width -->
<!--<div class="container u-max-full-width">  <!-- a little wider -->
<div class="container u-full-width u-max-full-width"> <!-- full width but disturbs search pane titles -->
<!-- <div class="container" style="width:98% !important;"> --> <!-- no effect -->

<?php
// ** ABOUT *****************************************************************
?>
<div class="section row" id="about">
  <h1>
    <img src="HaskellLogoGrey.png" style="height:1em; position:relative; top:3px;" />
    <a href="https://haskell-links.org"
       style="text-decoration:none; color:black;">Haskell Links Library</a>
  </h1>

  <!-- <p><a href="#" hx-post="/clickme" hx-swap="outerHTML">Click Me!</a></p> -->

  <p>
    A searchable collection of <a href="https://haskell.org">Haskell</a> links,
    currently <a href="https://github.com/simonmichael/lambdabot-where">lambdabot's</a>
    <!-- (managed in <a href="https://web.libera.chat/#haskell">#haskell</a> with <tt>@where</tt>) -->
    plus a few <a href="https://github.com/simonmichael/haskell-links/blob/main/in/manual.csv">more</a>.
    <!-- Shift-click column headings for multi-sort. -->

    ( <a href="https://github.com/simonmichael/haskell-links" onclick="$('#about-text').toggle(); return false;">About</a>
    <span id="about-text" style="display:none;">:
    <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>,
    <a href="https://github.com/simonmichael/haskell-links#data">implementation</a>,
    <a href="https://github.com/simonmichael/haskell-links/commits/main">changes</a>;
    would you like to
    <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">help</a> ?
    </span>).

    Example link searches:
    <a href="?q=book">book</a>,
    <a href="?q=paper">paper</a>,
    <a href="?q=learn">learn</a>,
    <a href="?q=tutorial">tutorial</a>,
    <a href="?q=ghc">ghc</a>,
    <a href="?q=cabal">cabal</a>,
    <a href="?q=stack">stack</a>,
    <a href="?q=paste">paste</a>,
    <a href="?q=game">game</a>.

    <br>
    And more Haskell search tools:<br>
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
// ** TABLE *******************************************************************
?>
<div class="section row" id="table">
  <table id="links" class="u-full-width u-max-full-width">
      <thead>
        <tr>
        <th>ID</th>
        <th>URL</th>
        <th>Description</th>
        <th>Tags</th>
      </tr>
    </thead>
  </table>
</div>
<noscript style="font-style:italic;">
  Sorry, the link search currently requires javascript.
</noscript>

</div>
</body>
</html>

<?php
}
?>
