<div class="tabber">
	<div class="tabber-navigation"> 
		<?php echo $this->renderPartial('_wikimenu'); ?>
	</div> 					
	<div class="panel">
	   <?php echo $this->renderPartial('_pageslist', array('rows' => $rows, 'display' => $this->display)); ?>              
	</div>
    <div class="tabber-navigation bottom">
        <ul>
            <li class="alignright"></li>
        </ul>
    </div><!-- #nav-above -->
</div><!-- End #ticket-manager --> 