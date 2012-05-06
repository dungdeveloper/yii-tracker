<?php if(isset($_POST['preview']) && $comment->content): ?>
    <?php echo $this->renderPartial('_preview', array('content' => $comment->content, 'title' => $props->title)); ?>
<?php endif; ?>

<div id="ticket-manager" class="tabber"> 
	<?php echo $this->renderPartial('_ticketsmenu'); ?>				
	<div id="show-ticket" class="panel">
	   <ol class="ticket-list"> 
            <li id="single-ticket" class="ticket"> 
                <p class="ticket-author"><?php echo Yii::t('tickets', 'By <strong>{name}</strong> On <em>{date}</em>', array('{name}' => $ticket->reporter ? $ticket->reporter->getLink(null, array('title' => Yii::t('tickets', 'View Profile'))) : Yii::t('tickets', 'Guest'), '{date}' => Yii::app()->dateFormatter->formatDateTime($ticket->posted, 'short', ''))); ?></p> 
                <?php if($ticket->status): ?>
                    <?php echo $ticket->status->getLink($ticket->status->title, array('class' => 'ticket-status ' . $ticket->status->alias, 'style' => 'background-color:'.$ticket->status->backgroundcolor.';color:'.$ticket->status->color.';' )); ?>	
            	<?php endif; ?> 
				<h1 class="ticket-title">
        		    <?php echo $ticket->getLink(CHtml::encode($ticket->title), array('title' => Yii::t('tickets', 'Permalink to {name}', array('{name}' => CHtml::encode($ticket->title))), 'rel' => 'bookmark', 'name' => 'ticketid-'.$ticket->id, 'class' => 'ticketTitleEditable')); ?>
        		</h1> 
				<ul class="ticket-meta single">				
                    <li>
                    	<small><?php echo Yii::t('tickets', 'Ticket'); ?></small>
                    	<?php echo $ticket->getLink('#'.$ticket->id, array('title' => Yii::t('tickets', 'Permalink to {name}', array('{name}' => CHtml::encode($ticket->title))), 'rel' => 'bookmark')); ?>
                    </li>
                    <li><small><?php echo Yii::t('tickets', 'Priority'); ?></small><?php echo $ticket->ticketpriority ? $ticket->ticketpriority->getLink(null, array('rel' => 'tag')) : '--'; ?></li>
                    <li><small><?php echo Yii::t('tickets', 'Type'); ?></small><?php echo $ticket->type ? $ticket->type->getLink(null, array('rel' => 'tag')) : '--'; ?></li>
                    <li><small><?php echo Yii::t('tickets', 'Tags'); ?></small><?php echo $ticket->getKeywords(); ?></li>
                    <li><small><?php echo Yii::t('tickets', 'Category'); ?></small><?php echo $ticket->category ? $ticket->category->getLink(null, array('rel' => 'tag')) : '--'; ?></li>
                    <li><small><?php echo Yii::t('tickets', 'Version'); ?></small><?php echo $ticket->version ? $ticket->version->getVersionLink() : '--'; ?></li>
                    <li><small><?php echo Yii::t('tickets', 'Fixed In'); ?></small><?php echo $ticket->fixed ? $ticket->fixed->getFixedInLink() : '--'; ?></li>
                    <li><small><?php echo Yii::t('tickets', 'Assigned To'); ?></small><?php echo $ticket->assigned ? $ticket->assigned->getLink(null, array('title' => Yii::t('tickets', 'View Profile'))) : '--'; ?></li>
                </ul> 
																		
				<div class="entry single-ticket">
				    <?php $this->beginWidget('CHtmlPurifier'); ?>
        	           <?php echo $ticket->content; ?> 
        	       <?php $this->endWidget(); ?>
				</div> 							
            </li> 
            
            <?php if(isset($ticket->comments) && count($ticket->comments)): ?>
                <?php foreach($ticket->comments as $comment): ?>
                    <?php if($comment->firstcomment): ?>
                    <?php continue; ?>
                    <?php endif; ?>
                    <li class="comment ticket alt" name="comment-<?php echo $comment->id; ?>"> 		
                		<div class="ticket-gravatar">
                            <?php if($comment->commentreporter): ?>
                                <?php echo $comment->commentreporter->getLink( CHtml::image('http://gravatar.com/avatar/'.md5($comment->commentreporter->email).'?s=29&d=mm', 'avatar', array('class' => 'avatar', 'width' => 29, 'height' => 29)) ); ?>
                            <?php else: ?>
                                <?php echo CHtml::image('http://gravatar.com/avatar/?s=29&d=mm', 'avatar', array('class' => 'avatar', 'width' => 29, 'height' => 29)); ?>
                            <?php endif; ?>
                		</div> 
                        <div class="ticket-info"> 
                			<p class="ticket-author"> 
                				<strong><?php echo $comment->commentreporter ? $comment->commentreporter->getLink() : Yii::t('global', 'Guest'); ?></strong><br /> 
                				<small><em title="<?php echo Yii::app()->dateFormatter->formatDateTime($comment->posted, 'long', 'medium'); ?>"><?php echo Yii::app()->dateFormatter->formatDateTime($comment->posted, 'short', 'short'); ?></em></small> 
                			</p> 
                			<div class="reply"> 
                			     <?php $this->beginWidget('CHtmlPurifier'); ?>
                    	           <p><?php echo $comment->content; ?></p> 
                                <?php $this->endWidget(); ?>
            				    <?php if($comment->changes): ?>
            				        <ol class="update-list"> 
            				            <?php foreach($comment->changes as $change): ?>
            				                <li><?php echo $change->content; ?></li>
            				            <?php endforeach; ?>
                				    </ol>
            				    <?php endif; ?>		
                			</div> 
                		</div> 
                    </li>
                <?php endforeach; ?>
			<?php endif; ?>			
						
            <div id="respond"> 
            
                <?php echo CHtml::errorSummary($props); ?>
                <?php echo CHtml::errorSummary($ticket); ?>
                <?php echo CHtml::errorSummary($comment); ?>
                <br />
                
                <?php echo CHtml::beginForm(); ?>
		          <fieldset> 
                    <legend><?php echo Yii::t('tickets', "Update '{title}'", array('{title}' => CHtml::encode($ticket->title))); ?></legend> 	
        				<p id="ticket-comment">
                        	<?php echo CHtml::label( Yii::t('tickets', 'Comment') . ' <em>'.Yii::t('tickets', '(Optional)').'</em>', false); ?>
                        	<?php $this->widget('widgets.markitup.markitup', array( 'model' => $comment, 'attribute' => 'content' )); ?>
        	                <?php echo CHtml::error($comment, 'content'); ?>
                        </p>
                        
                        <p id="ticket-title">
                        	<?php echo CHtml::activeLabel($props, 'title'); ?>
                        	<?php echo CHtml::activeTextField($props, 'title'); ?>
                        	<?php echo CHtml::error($props, 'title'); ?>
                        </p>
                        
                        <p id="ticket-keywords">
                        	<?php echo CHtml::label( Yii::t('tickets', 'Keywords') . ' <em>'.Yii::t('tickets', '(Optional)').'</em>', false); ?>
                        	<?php echo CHtml::activeTextField($props, 'keywords'); ?>
                        	<?php echo CHtml::error($props, 'keywords'); ?>
                        </p>
        		  </fieldset> 
        		
        		  <fieldset> 
        		      <legend><?php echo Yii::t('tickets', 'Ticket Properties'); ?></legend>
			             <p id="ticket-status" class='inline-input'>
                        	<?php echo CHtml::activeLabel($props, 'status'); ?>
                        	<?php echo CHtml::activeDropDownList($props, 'status', CHtml::listData(TicketStatus::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Status --'))); ?>
                        </p>
                        <p id="ticket-priority" class='inline-input'>
                        	<?php echo CHtml::activeLabel($props, 'priority'); ?>
                        	<?php echo CHtml::activeDropDownList($props, 'priority', CHtml::listData(TicketPriority::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Priority --'))); ?>
                        </p>
                        <p id="ticket-type" class='inline-input'>
                        	<?php echo CHtml::activeLabel($props, 'type'); ?>
                        	<?php echo CHtml::activeDropDownList($props, 'type', CHtml::listData(TicketType::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Type --'))); ?>
                        </p>
                        <p id="ticket-category" class='inline-input'>
                        	<?php echo CHtml::activeLabel($props, 'category'); ?>
                        	<?php echo CHtml::activeDropDownList($props, 'category', CHtml::listData(TicketCategory::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Category --'))); ?>
                        </p>
                        <p id="ticket-version" class='inline-input'>
                        	<?php echo CHtml::activeLabel($props, 'version'); ?>
                        	<?php echo CHtml::activeDropDownList($props, 'version', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Version --'))); ?>
                        </p>
                        <p id="ticket-fixedin" class='inline-input'>
                        	<?php echo CHtml::activeLabel($props, 'fixedin'); ?>
                        	<?php echo CHtml::activeDropDownList($props, 'fixedin', CHtml::listData(TicketVersion::model()->findAll(), 'id', 'title'), array('prompt' => Yii::t('tickets', '-- Select Fixed In Version --'))); ?>
                        </p>
                        <p id="ticket-assignedto" class='inline-input'>
                        	<?php echo CHtml::label( Yii::t('tickets', 'Assigned To') . ' <em>'.Yii::t('tickets', '(Optional)').'</em>', false); ?>
                        	<?php 
                        	   $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                    'attribute' => 'assignedtoid',
                                    'model' => $props,
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
                        	<?php echo CHtml::error($props, 'assignedtoid', array('class' => 'errorMessage inline-input')); ?>
                        </p>   			
			     </fieldset>
                				
                				
        		<p class="bottom-buttons"> 
        			<?php echo CHtml::submitButton( Yii::t('global', 'Update Ticket'), array('name' => 'submit') ); ?>
                    <?php echo CHtml::submitButton( Yii::t('global', 'Preview'), array('name' => 'preview') ); ?>
        		</p> 
                <?php echo CHtml::endForm(); ?>
            </div>								
        </ol> 			
	</div>
</div>