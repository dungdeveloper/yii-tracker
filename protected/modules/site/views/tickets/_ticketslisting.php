<?php echo CHtml::beginForm(array('QuickModeration')); ?>
	<div id="recent-tickets" class='panel'>
    <?php if(count($tickets)): ?>
        <ol class="ticket-list" id='tickets-list'>
            <?php foreach($tickets as $ticket): ?>
                <?php echo $this->renderPartial('_ticketrow', array('ticket' => $ticket)); ?>
            <?php endforeach; ?>
        </ol>
    <?php else: ?>
        <div class="entry inner">
            <h2><?php echo Yii::t('tickets', 'There are no tickets to display.'); ?></h2>
        </div>
    <?php endif; ?>

    <div class="tabber-navigation bottom-expanded">
        <div class='options'>
            <div class='floatleft'>
                <?php $this->widget('widgets.TrackerPager', array('pages' => $pages)); ?>
            </div>
            <div class='floatright' id='ticketsMoreOptions'><?php if(count($tickets)): ?><?php echo CHtml::link(Yii::t('tickets', 'Options'), 'javascript:'); ?><?php endif; ?></div>
            <div class='clearboth'></div>
            <div class='ticket-moreoptions' id='ticketsMoreOptionsHidden'>
                <div>
                    <fieldset id="ticket-form">
                        <legend><?php echo Yii::t('tickets', 'Ticket Properties'); ?></legend>
                        <p id="ticket-status" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'status'); ?>
                        	<?php echo CHtml::activeDropDownList($moderation, 'status', CHtml::listData(TicketStatus::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Status --'))); ?>
                        </p>
                        <p id="ticket-priority" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'priority'); ?>
                        	<?php echo CHtml::activeDropDownList($moderation, 'priority', CHtml::listData(TicketPriority::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Priority --'))); ?>
                        </p>
                        <p id="ticket-type" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'type'); ?>
                        	<?php echo CHtml::activeDropDownList($moderation, 'type', CHtml::listData(TicketType::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Type --'))); ?>
                        </p>
                        <p id="ticket-category" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'category'); ?>
                        	<?php echo CHtml::activeDropDownList($moderation, 'category', CHtml::listData(TicketCategory::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Category --'))); ?>
                        </p>
                        <p id="ticket-version" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'version'); ?>
                        	<?php echo CHtml::activeDropDownList($moderation, 'version', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Version --'))); ?>
                        </p>
                        <p id="ticket-fixedin" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'fixedin'); ?>
                        	<?php echo CHtml::activeDropDownList($moderation, 'fixedin', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Fixed In Version --'))); ?>
                        </p>
                        <p id="ticket-assignedto" class='inline-input'>
                        	<?php echo CHtml::activeLabel($moderation, 'assignedtoid'); ?>
                        	<?php
                        	   $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                    'attribute' => 'assignedtoid',
                                    'model' => $moderation,
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
                        </p>
                    </fieldset>
                    
                </div>
                
                <?php echo CHtml::button(Yii::t('tickets', 'Select All'), array('id' => 'ticketsSelectAll')); ?>
                &nbsp;
                <?php echo CHtml::button(Yii::t('tickets', 'Unselect All'), array('id' => 'ticketsUnSelectAll')); ?>
                &nbsp;
                <?php echo CHtml::submitButton(Yii::t('tickets', 'Update Selected Tickets'), array('name' => 'quickModeration')); ?>
            </div>
        </div>
    </div><!-- #nav-above -->
	
	</div>
    <?php echo CHtml::endForm(); ?>