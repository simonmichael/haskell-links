<?php
// echo '<small><pre>'; var_export($_SERVER); echo '</pre></small>';
// * haskell-links.org/index.php

require 'lib.php';

$links = readLinks();

// ** Router

$uri = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
$q = getdef($_GET['q']);

// no paging for no-js users, currently
// $pagelength = getdef($_GET['len']);

switch ($uri) {
  case '' :
  case '/' :
    index();
    break;
  default:
    $id = preg_replace('/^\//','', $uri);
    $url = findUrlById($id);
    if ($url) {
      header("Location: $url");
    }
    else {
      http_response_code(404);
      notfound();
    }
    break;
}

// ** 404 page

function notfound() {
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<h1>Not found</h1>
<a href="/" style="font-size:large;">Go back...</a>
</body>
</html>
<?php
}
  
// ** Index page

function index() {
  global $links, $q;
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
  <?php if (isset($_GET['q'])) print '<meta name="robots" content="noindex">' . PHP_EOL; ?>

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="HaskellLogoGrey.png">

  <!-- Font
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css"> -->

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="normalize.css">
  <link rel="stylesheet" href="skeleton.css">
  <link rel="stylesheet" href="datatables.min.css"/>
  <style><?php require 'styles.css'; ?></style>

  <!-- JS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script src="datatables.min.js"></script>
  <script><?php require 'scripts.js'; ?></script>
  <!-- <script src="htmx.min.js" defer></script> -->

</head>

<body>
<!-- <div class="container"> <!-- not full width -->
<!--<div class="container u-max-full-width">  <!-- a little wider -->
<div class="container u-full-width u-max-full-width"> <!-- full width but disturbs search pane titles -->
<!-- <div class="container" style="width:98% !important;"> --> <!-- no effect -->

<!-- About
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
<section id="heading">
  <h1>
    <img src="HaskellLogoGrey.png" style="height:1em; position:relative; top:3px;" />
    <a href="/" style="text-decoration:none; color:black;">Haskell Links Library</a>
  </h1>
</section>

<section id="about">
  <a id="aboutlink" onclick="aboutToggle(); searchFocus(); return false;">▼ About</a>
  <div id="aboutcontent">

    <div>
      - Search <a href="https://haskell.org">Haskell</a> links
        from <a href="https://github.com/simonmichael/lambdabot-where">lambdabot</a>
        and <a href="https://github.com/simonmichael/haskell-links-data">elsewhere</a>,
        below.
      <!-- (managed in <a href="https://web.libera.chat/#haskell">#haskell</a> with <tt>@where</tt>) -->
      <!-- Shift-click column headings for multi-sort. -->
      Example searches:<br>
      <a href="?q=book">book</a>,
      <a href="?q=paper">paper</a>,
      <a href="?q=learn">learn</a>,
      <a href="?q=tutorial">tutorial</a>,
      <a href="?q=-guide">-guide</a>,
      <a href="?q=ghc">ghc</a>,
      <a href="?q=cabal">cabal</a>,
      <a href="?q=stack">stack</a>,
      <a href="?q=paste">paste</a>,
      <a href="?q=irc">irc</a>,
      <a href="?q=matrix">matrix</a>,
      <a href="?q=game">game</a>.
      More <a id="searchtipslink" href="#" onclick="$('#searchtips').slideToggle('fast'); return false;">search tips...</a>
      <div id="searchtips">
        Search terms
        can match any column, 
        can be in any order,
        and are ANDed.
        Enclose multiword phrases in double quotes.
        Add column filters to refine your search.
        Shift-click column headings for multisort.
        Add/update lambdabot's links with LB's <a href="https://wiki.haskell.org/IRC_channel#lambdabot"><tt>@where</tt> command</a>.
        <!-- hll's links <a href="https://github.com/simonmichael/haskell-links-data/blob/main/hll.tsv">with git</a>. -->
      </div>
    </div>

    <div>
      - Jump to any link by typing <tt>haskell-links.org/ID</tt>.
        Examples:<br>
        <a href="https://haskell-links.org/doc">haskell-links.org/doc</a>,
        <a href="https://haskell-links.org/books">/books</a>,
        <a href="https://haskell-links.org/ghc-guide">/ghc-guide</a>,
        <a href="https://haskell-links.org/cabal-guide">/cabal-guide</a>,
        <a href="https://haskell-links.org/stack-guide">/stack-guide</a>
    </div>

    <div>
      - App
        <a href="https://github.com/simonmichael/haskell-links">source</a>,
        <a href="https://github.com/simonmichael/haskell-links-data">data</a>,
        <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>,
        <a href="https://github.com/simonmichael/haskell-links#data">design</a>,
        <a href="https://github.com/simonmichael/haskell-links#timeline">changes</a>,
        would you like to <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">discuss/help</a> ?
    </div>

    <div>
      - Find more Haskell search tools & resources here:
      <br>
      &nbsp;
      <a href="https://www.haskell.org/documentation">Official docs list</a> |
      <a href="https://www.extrema.is/articles/haskell-books">Books</a> |
      <a href="https://wiki.haskell.org">Wiki</a>
      (<a href="https://wiki.haskell.org/Special:RecentChanges">changes</a>, 
      <a href="https://wiki.haskell.org/index.php?title=Special:AllPages">pages</a>) |
      <a href="https://github.com/Gabriella439/post-rfc/blob/main/sotu.md#state-of-the-haskell-ecosystem">State of the Haskell ecosystem</a> |
      <a href="http://dev.stephendiehl.com/hask">What I Wish I Knew When Learning Haskell</a>
      <br>
      &nbsp;
      <a href="https://haskell.pl-a.net">Discussion feeds</a> |
      <a href="https://discourse.haskell.org">Discourse</a> |
      <a href="https://www.reddit.com/r/haskell/new">Reddit</a> |
      <a href="https://www.haskell.org/mailing-lists/">Mail lists</a> |
      <a href="https://www.haskell.org/irc/">IRC chat</a> |
      <a href="https://view.matrix.org/?query=haskell">Matrix chat</a> |
      <a href="https://stackoverflow.com/questions/tagged/haskell">Stack Overflow</a> |
      <a href="https://haskell.foundation/podcast">HF podcast</a> |
      <a href="https://twitter.com/HaskellOrg">Twitter</a>
      <br>
      &nbsp;
      <a href="https://cabal.readthedocs.io">Cabal user guide</a> |
      <a href="https://hackage.haskell.org/packages/browse">Hackage</a> |
      <a href="https://packdeps.haskellers.com">Hackage deps</a> |
      <a href="https://hoogle.haskell.org">Hoogle</a> |
      <a href="https://docs.haskellstack.org/en/stable/GUIDE">Stack user guide</a> |
      <a href="https://www.stackage.org/lts">Stackage LTS</a> |
      <a href="https://www.stackage.org/nightly">Stackage Nightly</a> |
      <a href="https://github.com/search/advanced">Github</a> |
      <a href="https://gitlab.com/explore/projects/topics/haskell">Gitlab</a> |
      <a href="https://www.libhunt.com/l/haskell">Popular projects</a> |
      <a href="https://www.libhunt.com/l/haskell/posts">Project mentions</a>
      <br>
      &nbsp;
      <a href="https://www.haskell.org/ghc">GHC</a> |
      <a href="https://downloads.haskell.org/ghc/latest/docs/html/users_guide">GHC user guide</a> |
      <a href="https://gitlab.haskell.org/ghc/ghc/-/wikis/home">GHC dev wiki</a>
      (<a href="https://gitlab.haskell.org/ghc/ghc-wiki-mirror/-/commits/master">changes</a>,
      <a href="https://gitlab.haskell.org/ghc/ghc/-/wikis/index">pages</a>) |
      <a href="https://gitlab.haskell.org/dashboard/projects?sort=stars_desc">GHC & core libs projects</a> |
      <a href="https://github.com/ghc-proposals/ghc-proposals#readme">GHC proposals</a> |
      <a href="https://github.com/haskell/core-libraries-committee#readme">Core libs proposals</a> |
      <a href="https://github.com/haskellfoundation/tech-proposals#readme">HF tech proposals</a>
      <br>
      &nbsp;
      <a href="https://github.com/simonmichael/haskell-links#related-projects--potential-link-sources">More links...</a>
    </div>

  </div>
</section>

<!-- Data table
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
<!-- should work either with DataTables (JS) or with server-side HTML (no JS -->

<section id="search">
  <div class="section row" id="table">
    <noscript>
      <!-- mimic DataTables html to preserve styling -->
      <div class="dataTables_wrapper">
      <form>
      <div id="links_filter" class="dataTables_filter">
        <label>Search:
          <input type="search" name="q" value="<?php global $q; echo $q ? htmlspecialchars($q) : '' ?>" placeholder="" aria-controls="links">
          <button id="search-btn">search</button>
        </label>
      </div>
    </noscript>
    <table id="links" class="u-full-width u-max-full-width dataTable">
        <thead>
          <tr>
            <th width="30%">URL</th>
            <th width="10%">ID</th>
            <th width="50%">Description</th>
            <th width="10%" style="text-align:right;">Source</th>
          </tr>
        </thead>
      <tbody class="nojs">
<?php
// global $num_matched;
$num_matched = 0;
$class = 'even';
foreach ($links as $r) {
  if (!queryMatchesRecord($q, $r)) continue;
  $num_matched++;
  $class = $class==='odd' ? 'even' : 'odd';
  echo "<tr class='$class'>
          <td class='url'><a href='{$r[0]}'>{$r[0]}</a></td>
          <td class='id'>{$r[1]}</td>
          <td class='desc'>{$r[2]}</td>
          <td class='source'>{$r[3]}</td>
        </tr>
        ";
        // <td class='tags'>"
        // . '<tt class=tag>' . join('</tt> <tt class=tag>', preg_split('/, */', $r[3])) . '</tt>' . "</td>
}
?>
      </tbody>
    </table>
    <noscript>
      </form>
      <div class="info-bottom">
        <div class="dataTables_info" id="links_info" role="status" aria-live="polite">
          <?php
            // global $num_matched, $links
            $num_total = count($links);
            echo "Showing 1 to $num_matched of $num_total entries";
          ?>
        </div>
      </div>
      </div>
    </noscript>
  </div>
</section>

</div>
</body>
</html>

<?php
}

