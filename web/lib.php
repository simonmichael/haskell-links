<?php
// * App PHP support code. Included by main page.

// ** DB

function readLinks() {
  // If the input arrays have the same string keys, then the later
  // value for that key will overwrite the previous one. If, however,
  // the arrays contain numeric keys, the later value will not
  // overwrite the original value, but will be appended.
  $links = array_merge(
    readLinksFrom('lambdabot'),
    readLinksFrom('hll')
    );
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

