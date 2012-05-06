<?php echo CHtml::beginForm(); ?>					
    <fieldset id="page-form">	
        <p id="wiki-title">
        	<?php echo CHtml::activeLabel($model, 'title'); ?>
        	<?php echo CHtml::activeTextField($model, 'title'); ?>
        	<?php echo CHtml::error($model, 'title'); ?>
        </p>
        <p id="wiki-description">
        	<?php echo CHtml::activeLabel($model, 'description'); ?>
        	<?php echo CHtml::activeTextArea($model, 'description'); ?>
        	<?php echo CHtml::error($model, 'description'); ?>
        </p>
        <p id="wiki-projectid">
        	<?php echo CHtml::activeLabel($model, 'projectid'); ?>
        	<?php echo CHtml::activeDropDownList($model, 'projectid', Projects::model()->getUserProjects(true), array('prompt' => Yii::t('wiki', '-- Choose Project --'))); ?>
        	<?php echo CHtml::error($model, 'projectid'); ?>
        </p>
        <p id="wiki-content">
        	<?php echo CHtml::activeLabel($revisionModel, 'content'); ?>
        	<?php $this->widget('widgets.markitup.markitup', array( 'model' => $revisionModel, 'attribute' => 'content' )); ?>
        	<?php echo CHtml::error($revisionModel, 'content'); ?>
        </p>
        <?php if($model->id): ?>
            <p id="wiki-comment">
                <?php echo CHtml::activeLabel($revisionModel, 'comment'); ?> <small><?php echo Yii::t('global', '(Optional)'); ?></small>
            	<?php echo CHtml::activeTextField($revisionModel, 'comment'); ?>
            	<?php echo CHtml::error($revisionModel, 'comment'); ?>
            </p>
        <?php endif; ?>
    </fieldset>
									
	<p class='bottom-buttons'>								
		<?php echo CHtml::submitButton( $model->id ? Yii::t('global', 'Update Page') : Yii::t('global', 'Create Page'), array('name' => 'submit') ); ?>
        <?php echo CHtml::submitButton( Yii::t('global', 'Preview'), array('name' => 'preview') ); ?>
	</p>
<?php echo CHtml::endForm(); ?>	