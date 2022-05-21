<?php
// * App PHP support code. Inlined into main page.

// ** DB

function readLinks() {
    $links = readLinksFrom('lambdabot');
    return $links;
}

$datadir = '../data';

function readLinksFrom($source) {
  global $datadir;
  $recs = [];
  if (($h = fopen("$datadir/$source.tsv", "r")) !== FALSE) {
    fgetcsv($h, null, "\t"); // skip header
    while (($r = fgetcsv($h, null, "\t")) !== FALSE) {
      $recs[] = $r;
    }
    fclose($h);
  }
  return $recs;
}

function findUrlById($id) {
  global $links;
  foreach ($links as $l) {
    if ($l[1] === $id) return $l[0];
  } 
  return null;
}

