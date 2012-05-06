<?php echo CHtml::beginForm(); ?>					
    <fieldset id="ticket-form">
        <legend><?php echo Yii::t('tickets', 'Ticket Information'); ?></legend>
        <p id="ticket-title">
        	<?php echo CHtml::activeLabel($model, 'title'); ?>
        	<?php echo CHtml::activeTextField($model, 'title'); ?>
        	<?php echo CHtml::error($model, 'title'); ?>
        </p>
        <p id="ticket-keywords">
        	<?php echo CHtml::label( Yii::t('tickets', 'Keywords') . ' <em>'.Yii::t('tickets', '(Optional)').'</em>', false); ?>
        	<?php echo CHtml::activeTextField($model, 'keywords'); ?>
        	<?php echo CHtml::error($model, 'keywords'); ?>
        </p>
        <p id="ticket-projectid">
        	<?php echo CHtml::activeLabel($model, 'projectid'); ?>
        	<?php echo CHtml::activeDropDownList($model, 'projectid', Projects::model()->getUserProjects(true), array('prompt' => Yii::t('tickets', '-- Choose Project --'))); ?>
        	<?php echo CHtml::error($model, 'projectid'); ?>
        </p>
        <p id="ticket-content">
        	<?php echo CHtml::activeLabel($model, 'content'); ?>
        	<?php $this->widget('widgets.markitup.markitup', array( 'model' => $model, 'attribute' => 'content' )); ?>
        	<?php echo CHtml::error($model, 'content'); ?>
        </p>
    </fieldset>
    <?php if(!$model->id): ?>
        <fieldset id="ticket-form">
            <legend><?php echo Yii::t('tickets', 'Ticket Properties'); ?></legend>
            <p id="ticket-status" class='inline-input'>
            	<?php echo CHtml::activeLabel($model, 'ticketstatus'); ?>
            	<?php echo CHtml::activeDropDownList($model, 'ticketstatus', CHtml::listData(TicketStatus::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Status --'))); ?>
            </p>
            <p id="ticket-priority" class='inline-input'>
            	<?php echo CHtml::activeLabel($model, 'priority'); ?>
            	<?php echo CHtml::activeDropDownList($model, 'priority', CHtml::listData(TicketPriority::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Priority --'))); ?>
            </p>
            <p id="ticket-type" class='inline-input'>
            	<?php echo CHtml::activeLabel($model, 'tickettype'); ?>
            	<?php echo CHtml::activeDropDownList($model, 'tickettype', CHtml::listData(TicketType::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Type --'))); ?>
            </p>
            <p id="ticket-category" class='inline-input'>
            	<?php echo CHtml::activeLabel($model, 'ticketcategory'); ?>
            	<?php echo CHtml::activeDropDownList($model, 'ticketcategory', CHtml::listData(TicketCategory::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Category --'))); ?>
            </p>
            <p id="ticket-version" class='inline-input'>
            	<?php echo CHtml::activeLabel($model, 'ticketversion'); ?>
            	<?php echo CHtml::activeDropDownList($model, 'ticketversion', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Version --'))); ?>
            </p>
            <p id="ticket-fixedin" class='inline-input'>
            	<?php echo CHtml::activeLabel($model, 'fixedin'); ?>
            	<?php echo CHtml::activeDropDownList($model, 'fixedin', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Fixed In Version --'))); ?>
            </p>
            <p id="ticket-assignedto" class='inline-input'>
            	<?php echo CHtml::label( Yii::t('tickets', 'Assigned To') . ' <em>'.Yii::t('tickets', '(Optional)').'</em>', false); ?>
            	<?php 
            	   $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'attribute' => 'assignedtoid',
                        'model' => $model,
                        'source' => $this->createUrl('/tickets/getUserNames'),
                        // additional javascript options for the autocomplete plugin
                        'options'=>array(
                            'minLength'=>'2',
                        ),
                        'htmlOptions'=>array(
                            'style'=>'height:20px;'
                        ),
                    ));
            	?>
            	<?php echo CHtml::error($model, 'assignedtoid', array('class' => 'errorMessage inline-input')); ?>
            </p>
        </fieldset>
    <?php endif; ?>
									
	<p class='bottom-buttons'>								
		<?php echo CHtml::submitButton( $model->id ? Yii::t('global', 'Update Ticket') : Yii::t('global', 'Create Ticket'), array('name' => 'submit') ); ?>
        <?php echo CHtml::submitButton( Yii::t('global', 'Preview'), array('name' => 'preview') ); ?>
	</p>
<?php echo CHtml::endForm(); ?>	