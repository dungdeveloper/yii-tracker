<li id="ticket-<?php echo $ticket->id; ?>" class="ticket"> 
    <p class="ticket-author"><?php echo Yii::t('tickets', 'By <strong>{name}</strong> On <em>{date}</em>', array('{name}' => $ticket->reporter ? $ticket->reporter->getLink(null, array('title' => Yii::t('tickets', 'View Profile'))) : Yii::t('tickets', 'Guest'), '{date}' => Yii::app()->dateFormatter->formatDateTime($ticket->posted, 'short', ''))); ?></p>
    <?php echo CHtml::link( CHtml::image(Yii::app()->themeManager->baseUrl . '/images/icons/edit.png', 'edit') , array('/tickets/edit', 'id' => $ticket->id), array('title' => Yii::t('tickets', 'Click to edit this ticket'))); ?>
    <?php echo CHtml::checkBox('tickets[]', (bool) isset($_POST['tickets']) && in_array($ticket->id, $_POST['tickets']), array('value' => $ticket->id)); ?>
    <?php if($ticket->status): ?>
        <?php echo $ticket->status->getLink($ticket->status->title, array('class' => 'ticket-status ' . $ticket->status->alias, 'style' => 'background-color:'.$ticket->status->backgroundcolor.';color:'.$ticket->status->color.';' )); ?>	
	<?php endif; ?>
		<h2 class="ticket-title">
		    <?php echo $ticket->getLink(CHtml::encode($ticket->title), array('title' => Yii::t('tickets', 'Permalink to {name}', array('{name}' => CHtml::encode($ticket->title))), 'rel' => 'bookmark', 'name' => 'ticketid-'.$ticket->id, 'class' => 'ticketTitleEditable')); ?>
		</h2> 
        <ul class="ticket-meta">				
            <li>
            	<small><?php echo Yii::t('tickets', 'Ticket'); ?></small>
            	<?php echo $ticket->getLink('#'.$ticket->id, array('title' => Yii::t('tickets', 'Permalink to {name}', array('{name}' => CHtml::encode($ticket->title))), 'rel' => 'bookmark')); ?>
            </li>
            <li><small><?php echo Yii::t('tickets', 'Comments'); ?></small><?php echo $ticket->commentsCount; ?></li>
            <li><small><?php echo Yii::t('tickets', 'Priority'); ?></small><?php echo $ticket->ticketpriority ? $ticket->ticketpriority->getLink(null, array('rel' => 'tag')) : '--'; ?></li>
            <li><small><?php echo Yii::t('tickets', 'Type'); ?></small><?php echo $ticket->type ? $ticket->type->getLink(null, array('rel' => 'tag')) : '--'; ?></li>
            <li><small><?php echo Yii::t('tickets', 'Tags'); ?></small><?php echo $ticket->getKeywords(); ?></li>
            <li><small><?php echo Yii::t('tickets', 'Category'); ?></small><?php echo $ticket->category ? $ticket->category->getLink(null, array('rel' => 'tag')) : '--'; ?></li>
            <li><small><?php echo Yii::t('tickets', 'Version'); ?></small><?php echo $ticket->version ? $ticket->version->getVersionLink() : '--'; ?></li>
            <li><small><?php echo Yii::t('tickets', 'Fixed In'); ?></small><?php echo $ticket->fixed ? $ticket->fixed->getFixedInLink() : '--'; ?></li>
            <li><small><?php echo Yii::t('tickets', 'Assigned To'); ?></small><?php echo $ticket->assigned ? $ticket->assigned->getLink(null, array('title' => Yii::t('tickets', 'View Profile'))) : '--'; ?></li>
        </ul>
</li>