<?php echo CHtml::beginForm(); ?>					
	<fieldset id="project-add">						
        <p id="project-title">
        	<?php echo CHtml::activeLabel($model, 'title'); ?>
        	<?php echo CHtml::activeTextField($model, 'title'); ?>
        	<?php echo CHtml::error($model, 'title'); ?>
        </p>
        <p id="project-description">
        	<?php echo CHtml::activeLabel($model, 'description'); ?>
        	<?php echo CHtml::activeTextArea($model, 'description'); ?>
        	<?php echo CHtml::error($model, 'description'); ?>
        </p>				
	</fieldset>
									
	<p class='bottom-buttons'>								
		<?php echo CHtml::submitButton($model->id ? Yii::t('project', 'Update Project') : Yii::t('global', 'Create Project')); ?>
	</p>
<?php echo CHtml::endForm(); ?>	