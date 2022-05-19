<?php
// echo '<small><pre>'; var_export($_SERVER); echo '</pre></small>';
// * haskell-links.org/index.php

require 'lib.php';

$links = readLinks();

// ** Router

$uri = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);

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
  global $links;
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
  <a id="aboutlink" onclick="aboutToggle(); return false;">&#x25BC; About</a>
  <div id="aboutcontent">

    <div>
      - App
        <a href="https://github.com/simonmichael/haskell-links">source</a>,
        <a href="https://github.com/simonmichael/haskell-links#readme">goals</a>,
        <a href="https://github.com/simonmichael/haskell-links#data">design</a>,
        <a href="https://github.com/simonmichael/haskell-links/commits/main">changes</a>,
        would you like to <a href="https://github.com/simonmichael/haskell-links#discuss--contribute">help</a> ?
    </div>

    <div>
      - Search <a href="https://haskell.org">Haskell</a> links
        collected by <a href="https://github.com/simonmichael/lambdabot-where">lambdabot</a> users, below.
      <!-- (managed in <a href="https://web.libera.chat/#haskell">#haskell</a> with <tt>@where</tt>) -->
      <!-- Shift-click column headings for multi-sort. -->
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
      - Jump to any link by typing <tt>haskell-links.org/ID</tt>.
        Examples:
        <a href="https://haskell-links.org/doc">haskell-links.org/doc</a>,
        <a href="https://haskell-links.org/books">/books</a>,
        <a href="https://haskell-links.org/ghc-guide">/ghc-guide</a>,
        <a href="https://haskell-links.org/cabal-guide">/cabal-guide</a>,
        <a href="https://haskell-links.org/stack-guide">/stack-guide</a>
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
      <a href="https://www.haskell.org/irc/">IRC channels</a> |
      <a href="https://view.matrix.org/?query=haskell">Matrix rooms</a> |
      <a href="https://stackoverflow.com/questions/tagged/haskell">Stack Overflow</a> |
      <a href="https://haskell.foundation/podcast">HF podcast</a>
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
      <a href="https://github.com/simonmichael/haskell-links#related-projects--link-sources">More...</a>
    </div>

  </div>
</section>

<!-- Data table
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
<!-- should work either with DataTables (JS) or with server-side HTML (no JS -->

<section id="search">
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
</section>

</div>
</body>
</html>

<?php
}

