<div id="project-manager" class="tabber"> 
	<div class="tabber-navigation"> 
		<?php echo $this->renderPartial('_projectsmenu'); ?>
	</div> 					
	<div id="create-ticket" class="panel">
					
		<div class="entry inner">
			<p><?php echo Yii::t('projects', 'Edit the project information, Make sure you fill all required fields.'); ?></p>
		</div>
				
		<div id="respond">					
                <?php echo $this->renderPartial('_projectform', array('model' => $model)); ?>											
		</div>				
	</div>
</div>