<ul>
  <li class="<?php echo Yii::app()->func->setClassByAction('index', 'current-tab'); ?>"><a href="<?php echo $this->createUrl('/projects'); ?>"><?php echo Yii::t('projects', 'Active Projects'); ?></a></li>	
  <li class='<?php echo Yii::app()->func->setClassByAction('archived', 'current-tab'); ?>'><a href="<?php echo $this->createUrl('/projects/archived'); ?>"><?php echo Yii::t('projects', 'Archived Projects'); ?></a></li>	
  <li class="<?php echo Yii::app()->func->setClassByAction('new', 'current-tab'); ?> alignright"><a href="<?php echo $this->createUrl('/projects/new'); ?>"><?php echo Yii::t('projects', 'New Project'); ?></a></li>			
</ul>