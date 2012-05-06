<div class="tabber">
	<div class="tabber-navigation"> 
		<ul>
          <li class="current-tab"><a href="<?php echo $this->createUrl('/activity'); ?>"><?php echo Yii::t('activity', 'Activity'); ?></a></li>
        </ul>
	</div> 				
	<div class="panel entry inner">
	   <?php if(count($activities)): ?>
	       <?php foreach($activities as $date => $rows): ?>
	           <h2><?php echo ($date == date('m-d-Y')) ? Yii::t('activity', 'Today') : $date; ?></h2>										
				<ol class="update-list">
				    <?php foreach($rows as $row): ?>
				    <li><strong class="title"><?php echo Yii::t('activity', 'Activity logged by {name}', array('{name}' => $row->author ? $row->author->getLink() : Yii::t('global', 'Guest'))); ?></strong>
						<ul class='activity-list'>
						  <li class='activity-type-<?php echo $row->type; ?>'><?php echo Yii::t('activity', $row->title, $row->getParams()); ?>
						      <?php if($row->description): ?><br /><small><?php echo Yii::t('activity', $row->description, $row->getParams()); ?></small><?php endif; ?>
						  </li>											
						</ul>
					</li>
					<?php endforeach; ?>							
				</ol>
	       <?php endforeach; ?>
	   <?php else: ?>
	       <p><?php echo Yii::t('activity', 'There is no activity yet.'); ?></p>
	   <?php endif; ?>     
	</div>
    <div class="tabber-navigation bottom">
        <ul>
            <li class="alignright"></li>
        </ul>
    </div><!-- #nav-above -->
</div><!-- End #ticket-manager --> 