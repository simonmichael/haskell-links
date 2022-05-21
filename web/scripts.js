/* App-specific JS. Inlined into main page, php interpolation supported. */

// https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

var animationSpeed = 'fast';

function aboutToggle() {
  var about = $('#aboutcontent');
  var visible = !about.is(":visible");
  localStorage.setItem('haskell-links.about.visible', visible);
  aboutLinkUpdate(visible);
  about.slideToggle(animationSpeed);
}

function aboutLinkUpdate(visible) {
  var aboutlink = $('#aboutlink');
  aboutlink.text(aboutlink.text()
    .replace( /(►|▼)/, visible ? '▼' : '►')
    .replace( /(\.\.\.)?$/, visible ? '' : '...')
    );
  aboutlink.attr('href','#');  // also make it look like a hyperlink when js is enabled
}

function searchPanesToggle() {
  var searchpanes = $('.dtsp-panesContainer');
  var visible = searchpanes.is(":visible");
  searchpanes.slideToggle(animationSpeed);
  localStorage.setItem('haskell-links.searchpanes.visible', !visible);
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

  // show/hide about as before
  // XXX do this early, trying to minimise popping (about is visible by default, for no-js users)
  var aboutvisible = localStorage.getItem('haskell-links.about.visible') != 'false';
  aboutLinkUpdate(aboutvisible);
  var aboutcontent = $('#aboutcontent');
  if (aboutvisible)
    aboutcontent.show();
  else
    aboutcontent.hide();
  
  // set up data table, https://datatables.net
  var table = $('table#links').DataTable({
    // https://datatables.net/manual/options
    data: <?php echo json_encode($links) ?>,
    // order: [[0,'asc']],  // the default
    columns: [
      {
        className: 'url',
        render: function(data, type, row) { 
          if (type === 'display')
            return '<a href="'+data+'">'+data+'</a>';
          else  // sort, filter, type, sp
            return data;
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
        className: 'tags',
      },
    ],
    fixedHeader: true,
    language: {
      zeroRecords: "No matching links",
    },
    bAutoWidth: true,  // true adjusts column widths, false avoids table width change when empty
    // dom: '<"info-top"i>fB' + (params['advanced'] ? '<"#searchpanes"P>' : '') + 'rtpl<"info-bottom"i>',
    dom: '<"info-top"i>fB<"#searchpanes"P>rtpl<"info-bottom"i>',
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
      return false;
    }
  });

  // if (params['advanced']) {

  // insert column filters toggle button
  $('<div id="column_filters"><button onclick="searchPanesToggle()">column filters</button></div>').insertAfter(search);
  // move filter count after it
  $('#column_filters').append($('.dtsp-title'));

  // insert column filters toggle button, and merge the active filters count with it
  // XXX needs to be updated when search pane selections change
  // var filters_msg = $('.dtsp-title');
  // var numfilters = filters_msg.text().match(/[0-9]+$/)[0];
  // var filters_button_txt = 'column filters' + (numfilters==='0' ? '' : (' (' + numfilters + ')'))
  // filters_msg.remove();
  // $('<div id="column_filters"><button onclick="searchPanesToggle()">'+filters_button_txt+'</button></div>').insertAfter(search);

  // }

  // insert "search" button that also updates url, just for clarity
  $('<button id="search-btn" onclick="setUrlFromSearch()">save search</button>').insertAfter(search);

  // show/hide column filters (searchpanes) as before
  var searchpanes = $('.dtsp-panesContainer');
  var searchpanesvisible = localStorage.getItem('haskell-links.searchpanes.visible') == 'true';
  if (searchpanesvisible)
    searchpanes.show();
  else
    searchpanes.hide();

  // problematic, also triggers on column sort
  // table.on('search.dt', function (e) {
  //   if (!search.val()) updateLocationFromSearch();   // update location on clearing the search
  // });

  if (params.q) table.search(params.q).draw();  // search for the q parameter if any

  $('input[type=search]').focus();  // await input

});
