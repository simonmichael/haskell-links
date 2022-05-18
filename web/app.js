/* App-specific JS, inlined into main page */

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
  // update url when enter is pressed
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