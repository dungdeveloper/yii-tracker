/**
* Projects Section JS File
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
    
    // Change project status
    $('.__changeStatus').click(function() {
        var projectId = $(this).attr('id').replace(/projectspan-/, '');
        $.ajax({ 
            url: Tracker.settings.baseUrl + '/projects/status',
            data: { 'id': projectId }, 
            success: function(data){
                $('#projectid-' + projectId).html( '<i>' + data + '</i>' ).fadeOut(1500);
            }
        });
        $(".tipsy").remove(); // bug in tipsy
        return false;
    });
});