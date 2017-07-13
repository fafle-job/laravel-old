$(function() {

    var $table2 = $( '#table2' )
        .tablesorter({
            theme : 'blue',
            // this is the default setting
            cssChildRow : 'tablesorter-childRow',
            // initialize zebra and filter widgets
            widgets : [ 'zebra' ],
            widgetOptions : {
                // include child row content while filtering, if true
                filter_childRows : true,
                // filter child row content by column; filter_childRows must also be true
                filter_childByColumn : true,
                // class name applied to filter row and each input
                // Set this option to false to make the searches case sensitive
                filter_ignoreCase : true,
                filter_reset: '.reset'
            }
        });

    // hide child rows - don't use .hide() because filtered rows get a "filtered" class
    // added to them, and style="display: table-row;" will override this class
    // See http://jsfiddle.net/Mottie/u507846y/ & https://github.com/jquery/jquery/issues/1767
    // and https://github.com/jquery/jquery/issues/2308
    // This won't be a problem in jQuery v3.0+
    $table2.find( '.tablesorter-childRow' ).addClass( 'hidden' );
    // Toggle child row content (td), not hiding the row since we are using rowspan
    // Using delegate because the pager plugin rebuilds the table after each page change
    // "delegate" works in jQuery 1.4.2+; use "live" back to v1.3; for older jQuery - SOL
    $table2.delegate( '.toggle', 'click' ,function() {
        // use "nextUntil" to toggle multiple child rows
        // toggle table cells instead of the row
        $( this )
            .closest( 'tr' )
            .nextUntil( 'tr.tablesorter-hasChildRow' )
            .toggleClass( 'hidden' );
        return false;
    });

    // Toggle filter_childByColumn option
    $( 'button.toggle-byColumn' ).click(function(){
        var wo = $table2[0].config.widgetOptions,
            o = !wo.filter_childByColumn;
        wo.filter_childByColumn = o;
        $('.state2').html( o.toString() );
        // update filter; include false parameter to force a new search
        $table2.trigger('search', false);
        return false;
    });

    // make demo search buttons work


});