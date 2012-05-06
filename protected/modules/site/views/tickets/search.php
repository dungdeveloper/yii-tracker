<div id="ticket-search" class="tabber"> 
	<?php echo $this->renderPartial('_ticketsmenu', array('searchtotal' => $count)); ?>			
	<div id="search-tickets" class="panel">
		<div class="entry inner">
		    <h2><?php echo $title; ?></h2>
			<p><?php echo Yii::t('tickets', 'Search tickets by filling out the fields in the following form.'); ?></p>
		    <?php if($search->hasErrors()): ?>
                <p><?php echo CHtml::errorSummary($search); ?></p>
		    <?php endif; ?>  
		</div>
		
		<div id='searchclosed' class='bluebox smallpadding' style="cursor:pointer;display:<?php if($doSearch): ?><?php else: ?>none<?php endif; ?>;"><h3><?php echo Yii::t('tickets', 'Show Search Form'); ?></h3></div>
				
		<div id="advsearchform" class='bluebox' style="display:<?php if($doSearch): ?>none<?php endif; ?>;">					
            <?php echo CHtml::beginForm(array('/tickets/search')); ?>					
                <fieldset id="ticket-form">
                    <legend><?php echo Yii::t('tickets', 'Ticket Information'); ?></legend>
                    <p id="ticket-title">
                    	<?php echo CHtml::activeLabel($search, 'title'); ?>
                    	<?php echo CHtml::activeTextField($search, 'title'); ?>
                    	<?php echo CHtml::error($search, 'title'); ?>
                    </p>
                    <p id="ticket-keywords">
                    	<?php echo CHtml::label( Yii::t('tickets', 'Keywords') . ' <em>'.Yii::t('tickets', '(Optional)').'</em>', false); ?>
                    	<?php echo CHtml::activeTextField($search, 'keywords'); ?>
                    	<?php echo CHtml::error($search, 'keywords'); ?>
                    </p>
                </fieldset>
                <fieldset id="ticket-form">
                    <legend><?php echo Yii::t('tickets', 'Ticket Properties'); ?></legend>
                    <p id="ticket-status" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'status'); ?>
                    	<?php echo CHtml::activeListBox($search, 'status', CHtml::listData(TicketStatus::model()->findAll(), 'id', 'title'), array('multiple' => 'multiple')); ?>
                    </p>
                    <p id="ticket-priority" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'priority'); ?>
                    	<?php echo CHtml::activeListBox($search, 'priority', CHtml::listData(TicketPriority::model()->findAll(), 'id', 'title'), array('multiple' => 'multiple')); ?>
                    </p>
                    <p id="ticket-type" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'type'); ?>
                    	<?php echo CHtml::activeListBox($search, 'type', CHtml::listData(TicketType::model()->findAll(), 'id', 'title'), array('multiple' => 'multiple')); ?>
                    </p>
                    <p id="ticket-category" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'category'); ?>
                    	<?php echo CHtml::activeListBox($search, 'category', CHtml::listData(TicketCategory::model()->findAll(), 'id', 'title'), array('multiple' => 'multiple')); ?>
                    </p>
                    <p id="ticket-version" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'version'); ?>
                    	<?php echo CHtml::activeListBox($search, 'version', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('multiple' => 'multiple')); ?>
                    </p>
                    <p id="ticket-fixedin" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'fixedin'); ?>
                    	<?php echo CHtml::activeListBox($search, 'fixedin', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('multiple' => 'multiple')); ?>
                    </p>
                    <p id="ticket-reportedby" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'reportedby'); ?>
                    	<?php 
                    	   $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'attribute' => 'reportedby',
                                'model' => $search,
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
                    	<?php echo CHtml::error($search, 'reportedby', array('class' => 'errorMessage inline-input')); ?>
                    </p>
                    <p id="ticket-assignedto" class='inline-input'>
                    	<?php echo CHtml::activeLabel($search, 'assignedtoid'); ?>
                    	<?php 
                    	   $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'attribute' => 'assignedtoid',
                                'model' => $search,
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
                    	<?php echo CHtml::error($search, 'assignedtoid', array('class' => 'errorMessage inline-input')); ?>
                    </p>
                </fieldset>
            									
            	<p class='bottom-buttons'>					
            		<?php echo CHtml::submitButton( $doSearch ? Yii::t('global', 'Update Search!') : Yii::t('global', 'Search!'), array('name' => 'submit') ); ?>
            		<?php echo CHtml::submitButton( Yii::t('global', 'Reset'), array('name' => 'reset') ); ?>
            	</p>
            <?php echo CHtml::endForm(); ?>										
		</div>
		
		<!-- tickets list -->
		<?php if($doSearch): ?>
    		<?php echo $this->renderPartial('_ticketslisting', array('pages' => $pages, 'moderation' => $moderation, 'tickets' => $tickets)); ?>
        <?php endif; ?>
		<!-- tickets list -->
						
	</div>
</div>