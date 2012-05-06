<div class="tabber-navigation"> 
	<ul>
	    <li class="<?php echo Yii::app()->func->setClassByAction(array('index', 'filterissues', 'filterissuesbytag'), 'current-tab'); ?>">
			<a href="<?php echo $this->createUrl('/tickets'); ?>"><?php echo (isset($total) && $total) ? Yii::t('tickets', 'Tickets ({n})', array('{n}' => $total)) : Yii::t('tickets', 'Tickets'); ?></a>
		</li>
		<li class="<?php echo Yii::app()->func->setClassByAction(array('search'), 'current-tab'); ?>">
			<a href="<?php echo $this->createUrl('/tickets/search'); ?>"><?php echo (isset($searchtotal) && $searchtotal) ? Yii::t('tickets', 'Search ({n})', array('{n}' => $searchtotal)) : Yii::t('tickets', 'Search'); ?></a>
		</li>
		<li>
			<a href="javascript:"><?php echo Yii::t('tickets', 'Status'); ?></a>
			<ul class="children">
			    <?php foreach(TicketStatus::model()->with(array('ticketsCount'))->findAll() as $status): ?>
			        <li><?php echo $status->getLink('<span>'.Yii::app()->format->number($status->ticketsCount).'</span>'.CHtml::encode($status->title)); ?></li>
			    <?php endforeach; ?>
			</ul>
		</li>
		<li>
			<a href="javascript:"><?php echo Yii::t('tickets', 'Priority'); ?></a>
			<ul class="children">
			    <?php foreach(TicketPriority::model()->with(array('ticketsCount'))->findAll() as $priority): ?>
			        <li class='cat-item'><?php echo $priority->getLink('<span>'.Yii::app()->format->number($priority->ticketsCount).'</span>'.CHtml::encode($priority->title)); ?></li>
			    <?php endforeach; ?>
			</ul>
		</li>
		<li>
			<a href="javascript:"><?php echo Yii::t('tickets', 'Type'); ?></a>
			<ul class="children">
			    <?php foreach(TicketType::model()->with(array('ticketsCount'))->findAll() as $type): ?>
			        <li><?php echo $type->getLink('<span>'.Yii::app()->format->number($type->ticketsCount).'</span>'.CHtml::encode($type->title)); ?></li>
			    <?php endforeach; ?>
			</ul>
		</li>
		<li>
			<a href="javascript:"><?php echo Yii::t('tickets', 'Milestone'); ?></a>
			<ul class="children">
			    <?php foreach(Milestones::model()->with(array('ticketsCount'))->findAll() as $category): ?>
			        <li><?php echo $category->getLink('<span>'.Yii::app()->format->number($category->ticketsCount).'</span>'.CHtml::encode($category->title)); ?></li>
			    <?php endforeach; ?>
			</ul>
		</li>					
		<li>
			<a href="javascript:"><?php echo Yii::t('tickets', 'Category'); ?></a>
			<ul class="children">
			    <?php foreach(TicketCategory::model()->with(array('ticketsCount'))->findAll() as $milestone): ?>
			        <li><?php echo $milestone->getLink('<span>'.Yii::app()->format->number($milestone->ticketsCount).'</span>'.CHtml::encode($milestone->title)); ?></li>
			    <?php endforeach; ?>
			</ul>
		</li>
		<li class="<?php echo Yii::app()->func->setClassByAction('create', 'current-tab'); ?> alignright">
			<a href="<?php echo $this->createUrl('/tickets/create'); ?>"><?php echo Yii::t('tickets', 'Create Ticket'); ?></a>
		</li>				
	</ul>
</div> 	