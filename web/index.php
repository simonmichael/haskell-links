<?php // echo '<small><pre>'; var_export($_SERVER); echo '</pre></small>'; ?>
<?php
// * haskell-links.org/index.php
// ** PHP *****************************************************************

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

$links = readLinks();

function findUrlById($id) {
  global $links;
  foreach ($links as $l) {
    if ($l[0] === $id) return $l[1];
  }
  return null;
}

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
    $id = preg_replace('/^\//','', $uri);
    $url = findUrlById($id);
    if ($url) {
      header("Location: $url");
    }
    else {
      echo "<h1>Not found</h1>";
      // require __DIR__ . '404.php';
      http_response_code(404);
    }
    break;
}

function index() {
  global $links;

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
  <link rel="stylesheet" href="normalize.css">
  <link rel="stylesheet" href="skeleton.css">
  <link rel="stylesheet" href="datatables.min.css"/>
  <style>

/* undo some skeleton css */
pre, blockquote, dl, figure, table, p, ul, ol, form, input, textarea, select, fieldset { margin-bottom: revert; }
a { color: revert; }
a:hover { color: revert; }
button, label { margin-bottom:revert; }
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
  /*background-color:#e0e0e0; */
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
  padding:1em;
}
#about > div {
  margin-top:.2em;
}
.dataTables_filter, .dataTables_info, .dt-buttons, .dtsp-title {
  margin-left: 1em;
  margin-right: 1em;
  
}
.info-top {
  text-align:center;
}
.dtsp-panesContainer {
  display: none;
}
.dtsp-title {
  display: inline-block;
  font-weight: normal;
  font-size: small;
}
div.dtsp-panesContainer button.dtsp-clearAll, div.dtsp-panesContainer button.dtsp-collapseAll, div.dtsp-panesContainer button.dtsp-showAll {
  padding-top:0;
}
/* div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane button.dtsp-paneButton, */
div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane input.dtsp-paneInputButton {
  background-color:white;
}
.dataTables_wrapper .dataTables_info {
  float:right;
  font-size:small;
}
#links_filter input[type=search] {
  margin-left:4px;
  width: 15em;
  font-size:medium;
  background-color: white;
  font-weight:bold;
  /* font-style:italic; */
}
#column_filters {
  display:inline-block;
  white-space:nowrap;
}
#links_filterX > label::after {
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
  padding-top: .5em;
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
  <script src="datatables.min.js"></script>
  <!-- <script src="htmx.min.js" defer></script> -->
  <script>

// https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

function searchPanesToggle() {
  var searchpanes = $('.dtsp-panesContainer');
  searchpanes.toggle();
  localStorage.setItem('haskell-links.searchpanes.visible', searchpanes.is(":visible"));
}

function setUrlFromSearch() {
  var search = $('#links_filter input[type=search]');
  var url = new URL(window.location.href);
  var currentsearchterm = search.val();
  if (currentsearchterm)
    url.searchParams.set('q', currentsearchterm);
  else
    url.searchParams.delete('q');
  window.location = url;
}

$(document).ready( function () {
  // https://datatables.net/manual
  var table = $('table#links').DataTable({
    // https://datatables.net/manual/options
    data: <?php echo json_encode($links) ?>,
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
    language: {
      zeroRecords: "No matching links",
    },
    fixedHeader: true,
    bAutoWidth: true,  // true adjusts column widths, false avoids table width change when empty
    order: [[1,'asc']],
    //dom: '<"info-top"i>fB<"#searchpanes"P>rtpl<"info-bottom"i>',
    dom: '<"info-top"i>fB<"#searchpanes">rtpl<"info-bottom"i>',
    searchPanes: {
      //initCollapsed: true,
      collapse: false,
      clear: true,
      controls: true,
      threshold: 1,
    },
    buttons: [
      // 'searchPanes',
      // 'copy',
    ],
    paging: false,
    // pageLength: -1,
    // lengthMenu: [100,200,500,'All'],
    // colReorder: true,
    // rowGroup: {
    //   dataSrc: 'tags',
    // },
    // stateSave: true,
  });

  var search = $('#links_filter input[type=search]');
  // updateee url when enter is pressed
  $(search).on('keypress', function(e) {
    if (e.which == 13) {
      setUrlFromSearch();  // update location on enter
    }
  });
  // // insert column filters toggle button
  // $('<div id="column_filters"><button onclick="searchPanesToggle()">column filters</button></div>').insertAfter(search);
  // // move filter count after it
  // $('#column_filters').append($('.dtsp-title'));
  // insert "search" button that also updates url, just for clarity
  $('<button id="search-btn" onclick="setUrlFromSearch()">save search</button>').insertAfter(search);

  // show/hide search panes as they were last time
  var searchpanes = $('.dtsp-panesContainer');
  var searchpanesvisible = localStorage.getItem('haskell-links.searchpanes.visible');
  if (searchpanesvisible != 'true')
    searchpanes.hide();
  else
    searchpanes.show();

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
    <a href="/" style="text-decoration:none; color:black;">Haskell Links Library</a>
  </h1>

  <div>
    - A searchable collection of <a href="https://haskell.org">Haskell</a> links,
      synced regularly from <a href="https://github.com/simonmichael/lambdabot-where">lambdabot</a>.
    <!-- (managed in <a href="https://web.libera.chat/#haskell">#haskell</a> with <tt>@where</tt>) -->
    <!-- plus a few <a href="https://github.com/simonmichael/haskell-links/blob/main/in/manual.csv">more</a> -->
    <!-- Shift-click column headings for multi-sort. -->

    (<a href="https://github.com/simonmichael/haskell-links" onclick="$('#about-text').toggle(); return false;">About</a
    ><span id="about-text" style="display:none;">:
    <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>,
    <a href="https://github.com/simonmichael/haskell-links#data">implementation</a>,
    <a href="https://github.com/simonmichael/haskell-links/commits/main">changes</a>;
    would you like to
    <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">help</a> ?</span>).

    Example searches:
    <a href="?q=book">book</a>,
    <a href="?q=paper">paper</a>,
    <a href="?q=learn">learn</a>,
    <a href="?q=tutorial">tutorial</a>,
    <a href="?q=-guide">-guide</a>,
    <a href="?q=ghc">ghc</a>,
    <a href="?q=cabal">cabal</a>,
    <a href="?q=stack">stack</a>,
    <a href="?q=paste">paste</a>,
    <a href="?q=game">game</a>
  </div>

  <div>
    - Also a redirector: jump to any of these via <tt>haskell-links.org/ID</tt>.
      Examples:
      <a href="https://haskell-links.org/doc">haskell-links.org/doc</a>,
      <a href="https://haskell-links.org/books">/books</a>,
      <a href="https://haskell-links.org/ghc-guide">/ghc-guide</a>,
      <a href="https://haskell-links.org/cabal-guide">/cabal-guide</a>,
      <a href="https://haskell-links.org/stack-guide">/stack-guide</a>
  </div>

  <div>
    - More Haskell search tools:
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
  </div>

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
