<?php
// * App PHP helpers, included by main page

// ** DB

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

function findUrlById($id) {
  global $links;
  foreach ($links as $l) {
    if ($l[0] === $id) return $l[1];
  }
  return null;
}

