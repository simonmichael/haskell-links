<?php
// * App PHP support code. Included by main page.

// ** Utils

// Safely get a value with default. (Until prod uses PHP >7 and can use ??)
function getdef(&$var, $default=null) {
  return isset($var) ? $var : $default;
}

// Is this predicate true for any of array's values ?
function any(callable $fn, array $array) {
  foreach ($array as $value) {
      if($fn($value)) {
          return true;
      }
  }
  return false;
}

// Get the unambiguous php code string representation of an object.
function repr($v) { return var_export($v,true); }

// Prepend a label to a string, if any, and append a newline.
function label($label,$s) { return ($label?"$label: ":"").$s; }

// // Remove one pair of enclosing single or double quotes, if any.
// function stripQuotes($text) {
//   return preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $text);
// }

/**
 * Debug-log and return a value.
 *
 * @param mixed    $obj         value to be logged. ActiveRecord objects are converted to hash form.
 * @param string   $label       label to prefix the value with.
 * @param string   $logfn       callable to use for logging. By default prints to stdout wrapped inside <pre>.
 * @return mixed                the same value that was logged
 */
function d(
	$obj,
  $label = '',
  $logfn = null
) {
	global $command_line, $debug_user, $_username;
	if ($debug_user && $_username != $debug_user) return $obj;
	$msg = label($label, repr($obj));
	$msg = rtrim($msg,"\n");
	if ($logfn)
    $logfn($msg);
  else 
    print "<pre>$msg</pre>\n";
	return $obj;
}

// Polyfills for prod's old PHP 5.6.

if (!function_exists('str_contains')) {
  function str_contains($haystack, $needle) {
      return $needle !== '' && mb_strpos($haystack, $needle) !== false;
  }
}

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

// ** Searching

function findUrlById($id) {
  global $links;
  foreach ($links as $l) {
    if ($l[1] === $id) return $l[0];
  } 
  return null;
}

// Server-side link record matching, used when there's no JS. 
// Mimics DataTables' client-side smart non-regex search, https://datatables.net/reference/api/search :
// - Match words out of order. For example if you search for Allan Fife it would match a row containing the words Allan and Fife, regardless of the order or position that they appear in the table.
// - Partial word matching. As DataTables provides on-the-fly filtering with immediate feedback to the user, parts of words can be matched in the result set. For example All will match Allan.
// - Preserved text. DataTables 1.10 adds the ability to search for an exact phrase by enclosing the search text in double quotes. For example "Allan Fife" will match only text which contains the phrase Allan Fife. It will not match Allan is in Fife.
// - (All words/terms must match somewhere. Case-insensitive.)
// An empty/blank query matches everything.
function queryMatchesRecord($q, $rec) {
  $ts = queryTerms($q);
  foreach ($ts as $t) {
    if (!queryTermMatchesRecord($t, $rec))
      return false;
  }
  return true;
}

// Split a query possibly containing multiple words or double-quoted phrases
// into single query terms.
function queryTerms($q) {
  if ($q && trim($q))
    return str_getcsv($q, ' ');
  else
    return [];
}

// Try to match one word [or substring, or double-quoted phrase] of the query against a record.
function queryTermMatchesRecord($term, $rec) {
  $t = strtolower($term);
  return 
    str_contains(strtolower($rec[0]), $t) ||
    str_contains(strtolower($rec[1]), $t) ||
    str_contains(strtolower($rec[2]), $t) ||
    str_contains(strtolower($rec[3]), $t);
}

