/**
* Tickets Section JS File
* @author Vadim
*/
$(function() {
    // Show The more options hidden div
    $("#ticketsMoreOptions").click(function(){
      if($('#ticketsMoreOptionsHidden').is(':visible')) {
        $('#ticketsMoreOptionsHidden').fadeOut('slow');
      } else {
        $('#ticketsMoreOptionsHidden').fadeIn('slow');
      }
    });
    // Mark all tickets on current page as check/unchecked
    $('#ticketsSelectAll').click(function() {
        $('#tickets-list :checkbox').each(function(i) {
            $(this).attr('checked', true);
        });
    });
    $('#ticketsUnSelectAll').click(function() {
        $('#tickets-list :checkbox').each(function(i) {
            $(this).attr('checked', false);
        });
    });
    $('#searchclosed h3').click(function() {
        $('#advsearchform').toggle('slow');
    });
});
