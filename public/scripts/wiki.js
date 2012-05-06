/**
* Wiki Section JS File
* @author Vadim
*/
$(function() {
    // Show row options when hovering
    $("li.ticket").mouseenter(function(){
      $('.project-options', $(this)).show();
    }).mouseleave(function(){
      $('.project-options', $(this)).hide();
    });
    $(".project-options > span").mouseenter(function(){
      $(this).css({ opacity: 1 });
    }).mouseleave(function(){
      $(this).css({ opacity: 0.3 });
    });
    
    // Change page status
    $('.__changeStatus').click(function() {
        var rowId = $(this).attr('id').replace(/pagespan-/, '');
        $.ajax({ 
            url: Tracker.settings.baseUrl + '/wiki/status',
            data: { 'id': rowId }, 
            success: function(data){
                $('#pageid-' + rowId).html( '<i>' + data + '</i>' ).fadeOut(1500);
            }
        });
        $(".tipsy").remove(); // bug in tipsy
        return false;
    });
    
    // Show the fancybox modal with the diff report
    $("#revisionDiffForm").bind("submit", function() {
        // Set the values
        $revisionFrom = $('input:radio[name=revisionFrom]:checked').val();
        $revisionTo = $('input:radio[name=revisionTo]:checked').val();
        
        // Make sure we chosen both
        if(!$revisionFrom || !$revisionTo) {
            alert(Tracker.phrases.revisionChooseIds);
            return false;
        }
        
        // Make sure the two are different
        if($revisionFrom == $revisionTo) {
            alert(Tracker.phrases.revisionChooseDiffIds);
            return false;
        }
    
        // Show loading
    	$.fancybox.showActivity();
    
    	$.ajax({
    		cache	: false,
    		dataType: 'json',
    		url		: Tracker.settings.baseUrl + '/wiki/revisionDiff',
    		data		: {'revisionFrom': $revisionFrom, 'revisionTo': $revisionTo},
    		success: function(data) {
    			$.fancybox(data.html, {'centerOnScroll': true, 'title': data.title});
    		}
    	});
    
    	return false;
    });
});