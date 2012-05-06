<div class="tabber">
	<div class="tabber-navigation"> 
		<?php echo $this->renderPartial('_projectsmenu'); ?>
	</div> 					
	<div class="panel">
	   <?php echo $this->renderPartial('_projectslist', array('projects' => $projects)); ?>              
	</div>
    <div class="tabber-navigation bottom">
        <ul>
            <li class="alignright"></li>
        </ul>
    </div><!-- #nav-above -->
</div><!-- End #ticket-manager --> 