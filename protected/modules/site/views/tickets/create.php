<?php if(isset($_POST['preview'])): ?>
    <?php echo $this->renderPartial('_preview', array('content' => $model->content, 'title' => $model->title)); ?>
<?php endif; ?>

<div id="ticket-manager" class="tabber"> 
	<?php echo $this->renderPartial('_ticketsmenu'); ?>				
	<div id="create-ticket" class="panel">
					
		<div class="entry inner">
		    <h2><?php echo $title; ?></h2>
			<p><?php echo Yii::t('tickets', 'Please fill in all required fields before submitting a new ticket. Make sure you searched for a similar ticket before submitting this one.'); ?></p>
		    <?php if($model->hasErrors()): ?>
                <p><?php echo CHtml::errorSummary($model); ?></p>
		    <?php endif; ?>  
		</div>
				
		<div id="respond">					
            <?php echo $this->renderPartial('_ticketform', array('model' => $model)); ?>										
		</div>				
	</div>
</div>