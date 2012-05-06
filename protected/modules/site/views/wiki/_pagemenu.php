<div class="tabber-navigation"> 
	<ul>
	  <?php if(isset($showFirstTab) && $showFirstTab): ?> 
        <li class="<?php echo Yii::app()->func->setClassByAction(array('viewpage', 'viewpagerevision'), 'current-tab'); ?>"><a name='content'><?php echo $title; ?></a></li>
      <?php endif; ?>
      <?php if(isset($showViewTab) && $showViewTab): ?> 
      <li><?php echo $model->getLink(); ?></li>	
      <?php endif; ?>
      <li class='<?php echo Yii::app()->func->setClassByAction('revisions', 'current-tab'); ?>'><a href="<?php echo $this->createUrl('/wiki/revisions', array('id' => $model->id)); ?>"><?php echo Yii::t('wiki', 'Revisions'); ?></a></li>	
      <li class="alignright"><a href="<?php echo $this->createUrl('/wiki/edit', array('id' => $model->id)); ?>"><?php echo Yii::t('wiki', 'Edit Page'); ?></a></li>	
    </ul>
</div> 	