<div id="ticket-manager" class="tabber">
    <?php echo $this->renderPartial('_ticketsmenu', array('total' => $total)); ?>				
	<?php echo $this->renderPartial('_ticketslisting', array('pages' => $pages, 'moderation' => $moderation, 'tickets' => $tickets)); ?>
</div><!-- End #ticket-manager --> 