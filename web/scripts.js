/* App-specific JS. Inlined into main page, php interpolation supported. */

// https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

var animationSpeed = 'fast';

function aboutToggle() {
  var about = $('#aboutcontent');
  var visible = !about.is(":visible");
  localStorage.setItem('about.visible', visible);
  aboutLinkUpdate(visible);
  about.slideToggle(animationSpeed);
}

function aboutLinkUpdate(visible) {
  var aboutlink = $('#aboutlink');
  aboutlink.text(aboutlink.text()
    // .replace( /(\.\.\.)?$/, visible ? '' : '...')
    // .replace( /(▸|▾)/, visible ? '▾' : '▸')
    // .replace( /(◂|▾)/, visible ? '▾' : '◂')
    // .replace( /(▶|▼)/, visible ? '▼' : '▶')
    // .replace( /(◀|▼)/, visible ? '▼' : '◀')
    .replace( /(▸|▾)/, visible ? '▾' : '▸')
    );
  aboutlink.attr('href','#');  // also make it look like a hyperlink when js is enabled
}

// Toggle the visual open/closed state of the column filters button.
function columnFiltersToggle() {
  var searchpanes = $('.dtsp-panesContainer');
  var visible = !searchpanes.is(":visible");
  localStorage.setItem('columnfilters.visible', visible);
  columnFiltersButtonUpdate(visible)
  searchpanes.slideToggle(animationSpeed);
}

// Update the visual state of the column filters button based on:
// - the desired column filters visibility state provided
// - the number of currently active filters.
function columnFiltersButtonUpdate(visible) {
  var num_filters = searchPaneTables().rows({selected: true}).data().toArray().length;
  $('#column_filters > button').text(
    'column filters '
    + (num_filters ? ` (${num_filters})` : '')
    + (visible ? ' ▼' : ' ▶')
    );
}

// Get a DataTable object representing all the column filters search panes.
function searchPaneTables() {
  return $('.dtsp-searchPanes .dataTables_scrollBody table.dataTable').DataTable();
}

function setUrlFromSearch() {
  var search = $('#links_filter input[type=search]');
  var currentsearchterm = search.val();
  newurl = currentsearchterm ? setUrlParam('q', currentsearchterm) : deleteUrlParam('q');
  window.location = newurl;
}

// Set a query parameter in the current URL, returning a new modified URL object.
function setUrlParam(name, val) {
  var newurl = new URL(window.location.href);
  newurl.searchParams.set(name, val);
  return newurl;
}

// Delete a query parameter from the current URL if present, returning a new modified URL object.
function deleteUrlParam(name) {
  var newurl = new URL(window.location.href);
  newurl.searchParams.delete(name);
  return newurl;
}

function searchFocus() {
  $('input[type=search]').focus();
}

// Try to parse a localstorage value as an integer, or return null if it does not exist.
function localStorageGetInt(name) {
  val = localStorage.getItem(name);
  return val ? parseInt(val) : null;
}

// Remove duplicates from and sort an array in place, and return it.
function nubSort(array) {
  return Array.from(new Set(array)).sort(function(a,b) { return a - b; });
}

// paging config.. oh my lord

// page length is set by a len query parameter, recalled from storage, or a default
var reqpagelength = params.len ? parseInt(params.len) : null;
if (reqpagelength) localStorage.setItem('pagelength',reqpagelength);  // if a query parameter, remember it
var storedpagelength = localStorageGetInt('pagelength');
var defpagelength = 50;
var pagelength = reqpagelength ? reqpagelength : (storedpagelength || defpagelength);

// build the page lengths list
// to reduce confusion... include any custom page length from the query, or previously stored
var pagelengths = [25, 50, 100, 500];
if (reqpagelength)
  pagelengths.push(reqpagelength);     // add new page length specified with ?len
else if (storedpagelength && storedpagelength != -1 && !pagelengths.includes(storedpagelength))
  pagelengths.push(storedpagelength);  // or custom page length stored previously
pagelengths = nubSort(pagelengths);    // remove duplicates
var lengthmenu = [pagelengths.concat([-1]), pagelengths.concat('All')]  // add All at the end

// Show the list of pages only when page length is not All.
function showHidePageList(pagelength) {
  var pagenumbers = $('.dataTables_paginate');
  if (pagelength != -1)
    pagenumbers.show();
  else
    pagenumbers.hide();
}

$(document).ready( function () {

  // On changing the page length using the selector..
  $('#links').on( 'length.dt', function ( e, settings, len ) {
    // remember the new page length
    localStorage.setItem('pagelength',len);
    // show the page list only if page length is not All
    showHidePageList(len);
    // unfocus the select to prevent it annoyingly popping up again when you press up/down to scroll
    $('select[name=links_length]').blur();
    // if there was a ?len query parameter, remove it (reloads the page)
    if (params.len) window.location = deleteUrlParam('len');
  });

  // show/hide things as before
  // Things must be visible initially so no-js users can see them,
  // unfortunately there is noticeable popping at page load for js users,
  // while js hides things which should be hidden. 
  // Try to do that as early as possible.
  $('#searchtips').hide();
  var aboutvisible = localStorage.getItem('about.visible') != 'false';
  aboutLinkUpdate(aboutvisible);
  var aboutcontent = $('#aboutcontent');
  if (aboutvisible)
    aboutcontent.show();
  else
    aboutcontent.hide();
  
  // set up the data table. https://datatables.net, https://datatables.net/manual/options
  var pagecontrols = '<"#pagecontrols"lpi>';
  var table = $('table#links').DataTable({
    dom: `<"#inputs"fB><"#searchpanes"P>${pagecontrols}rt${pagecontrols}`,
    paging: true, // always show page length selector
    pageLength: pagelength,
    lengthMenu: lengthmenu,
    order: [[3,'asc'], [0,'asc']],  // initially sort by (first) source then url
    columns: [
      {
        className: 'url',
        render: function(data, type, row) { 
          // data is a hyperlink; unhyperlink it for non-display uses
          if (type === 'display')
            return data;
          else  // sort, filter, type, sp
            return data.match(/href="(.*?)"/)[1];
        },
        searchPanes: {
          orthogonal: 'sp',
        }
      },
      {
        className: 'id',
      },
      {
        className: 'desc',
      },
      {
        className: 'source',
      },
    ],
    fixedHeader: true,
    language: {
      zeroRecords: "No matching links",
    },
    bAutoWidth: true,  // true adjusts column widths, false avoids table width change when empty
    colReorder: true,
    searchPanes: {
      collapse: false,
      clear: true,
      controls: true,
      threshold: 1,
    },
    buttons: [
      // 'searchPanes',
      // 'copy',
    ],
    // rowGroup: {
    //   dataSrc: 'source',
    // },
    // stateSave: true,
  });

  showHidePageList(pagelength);

  // On pressing enter in the search field, update url and reload the page.
  var search = $('#links_filter input[type=search]');
  $(search).on('keypress', function(e) {
    if (e.which == 13) {
      setUrlFromSearch();
      return false;
    }
  });

  // show/hide column filters as before
  var columnfiltersvisible = localStorage.getItem('columnfilters.visible') == 'true';
  if (columnfiltersvisible)
    $('.dtsp-panesContainer').show();
  else
    $('.dtsp-panesContainer').hide();

  // insert column filters toggle button, and show the filter count within it,
  // replacing the usual info text element.
  $('.dtsp-title').remove();
  $('<div id="column_filters"><button onclick="columnFiltersToggle()"></button></div>').insertAfter(search);
  columnFiltersButtonUpdate(columnfiltersvisible);

  // install an event handler to update the filter count
  searchPaneTables().on('select deselect', function (e, dt, type, indexes) {
    var columnfiltersvisible = $('.dtsp-panesContainer').is(":visible");
    columnFiltersButtonUpdate(columnfiltersvisible);
  });

  // insert "search" button that also updates url, just for clarity
  $('<button id="search-btn" onclick="setUrlFromSearch()">save search</button>').insertAfter(search);

  // problematic, also triggers on column sort
  // table.on('search.dt', function (e) {
  //   if (!search.val()) updateLocationFromSearch();   // update location on clearing the search
  // });

  if (params.q) table.search(params.q).draw();  // if there's a q parameter, search for that

  searchFocus();  // await input

});
