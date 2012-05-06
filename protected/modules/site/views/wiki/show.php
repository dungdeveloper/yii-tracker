<div class="tabber">
	<?php echo $this->renderPartial('_pagemenu', array('model' => $model, 'title' => $title, 'showFirstTab' => true)); ?>			
	<div class="panel entry inner">
	       <?php $this->beginWidget('CHtmlPurifier'); ?>
	           <?php echo $revisionModel->content; ?> 
	       <?php $this->endWidget(); ?>
	</div>
    <div class="tabber-navigation bottom">
        <ul>
            <li class="alignright"></li>
        </ul>
    </div><!-- #nav-above -->
</div><!-- End #ticket-manager --> 