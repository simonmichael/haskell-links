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
    <a href="/" style="text-decoration:none; color:black;">Haskell Links</a>
  </h1>
</section>

<section id="about">

<p>
Search <a href="https://haskell.org">Haskell</a>-related links.
Multiple terms are ANDed; enclose phrases in double quotes.
Examples:
<a href="?q=book">book</a>,
<a href="?q=paper">paper</a>,
<a href="?q=learn">learn</a>,
<a href="?q=faq">faq</a>,
<a href="?q=tutorial">tutorial</a>,
<a href="?q=-guide">-guide</a>,
<a href="?q=ghc">ghc</a>,
<a href="?q=cabal">cabal</a>,
<a href="?q=stack">stack</a>,
<a href="?q=paste">paste</a>,
<a href="?q=irc">irc</a>,
<a href="?q=matrix">matrix</a>,
<a href="?q=game">game</a>.

Or, jump to any link by typing <tt>haskell-links.org/ID</tt>.
Examples:
<a href="https://haskell-links.org/books">haskell-links.org/books</a>,
<a href="https://haskell-links.org/ghc-guide">haskell-links.org/ghc-guide</a>,
<a href="https://haskell-links.org/cabal-guide">haskell-links.org/cabal-guide</a>,
<a href="https://haskell-links.org/stack-guide">haskell-links.org/stack-guide</a>.

You can update links in the <a href="https://web.libera.chat/#haskell">#haskell IRC channel</a> (ask how, there);
<a href="https://github.com/simonmichael/lambdabot-where">changes</a> should appear here within 5 minutes.

More about this site:
<a href="https://github.com/simonmichael/haskell-links">main repo</a>,
<a href="https://github.com/simonmichael/haskell-links-data">data repo</a>,
<a href="https://github.com/simonmichael/haskell-links#timeline">timeline</a>,
<a href="https://github.com/simonmichael/haskell-links#discuss--contribute">contribute</a>.

Find more Haskell resources here: <b><a href="https://joyful.com/Haskell+map">Haskell map</a></b>.


<!-- old
and <a href="https://github.com/simonmichael/haskell-links-data">elsewhere</a>
Shift-click column headings for multi-sort.
Add column filters to refine your search.
Shift-click column headings for multisort.
hll's links <a href="https://github.com/simonmichael/haskell-links-data/blob/main/hll.tsv">with git</a>.
-->

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

