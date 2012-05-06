/**
* Global things required
*
*/

$(document).ready(function()
{
    // Ajax loading message    
    $.fn.ajaxStart(function() { 
        $("#report-pane, #report-loading").fadeIn(200);   // fast fade in of 200 mili-seconds
    }).ajaxStop(function() {
        $("#report-pane, #report-loading").hide();    // fast hide
    });
    // Tooltips
    $('[title]').tipsy({gravity: $.fn.tipsy.autoNS});
}); 

// Confirm delete of an item
$('.deleteConfirm').live('click', function() {
	if( confirm( Tracker.phrases.deleteConfirm ) )
	{
		location.href = $(this).attr('href');
	}
	else
	{
		return false;
	}
});

// Global Init
$.ajaxSetup({
  cache: false,
  error: function(data) {Tracker.settings.debugMode ? console.log(data) : alert(data);}
});

/* Create the tracker namespace */
var Tracker = function() {
    /**
	 * @var object Phrases
	 */
    var pharases = {};
    /**
	 * @var object settings
	 */
    var settings = {};
};