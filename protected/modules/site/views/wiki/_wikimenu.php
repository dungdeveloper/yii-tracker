<ul>
  <li class="<?php echo Yii::app()->func->setClassByAction('index', 'current-tab'); ?>"><a href="<?php echo $this->createUrl('/wiki'); ?>"><?php echo Yii::t('wiki', 'Active Pages'); ?></a></li>	
  <li class='<?php echo Yii::app()->func->setClassByAction('archived', 'current-tab'); ?>'><a href="<?php echo $this->createUrl('/wiki/archived'); ?>"><?php echo Yii::t('wiki', 'Archived Pages'); ?></a></li>
    <li>
    	<a href="#"><?php echo Yii::t('wiki', 'Sort By'); ?></a>
    	<ul class="children">
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => $this->display, 'order' => 'title', 'sort' => Functions::getSortBy('title'))); ?>"><?php echo Yii::t('wiki', 'Title'); ?></a></li>
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => $this->display, 'order' => 'created', 'sort' => Functions::getSortBy('created'))); ?>"><?php echo Yii::t('wiki', 'Created Date'); ?></a></li>
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => $this->display, 'order' => 'userid', 'sort' => Functions::getSortBy('userid'))); ?>"><?php echo Yii::t('wiki', 'Author'); ?></a></li>
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => $this->display, 'order' => 'projectid', 'sort' => Functions::getSortBy('projectid'))); ?>"><?php echo Yii::t('wiki', 'Project'); ?></a></li>
    	</ul>
    </li>
    <li>
    	<a href="#"><?php echo Yii::t('wiki', 'Display As'); ?></a>
    	<ul class="children">
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => 'normal', 'order' => $this->order, 'sort' => $this->sort)); ?>"><?php echo Yii::t('wiki', 'Normal'); ?></a></li>
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => 'list', 'order' => $this->order, 'sort' => $this->sort)); ?>"><?php echo Yii::t('wiki', 'List'); ?></a></li>
    		<li><a href="<?php echo $this->createUrl('/wiki/' . $this->getAction()->id, array('display' => 'nlist', 'order' => $this->order, 'sort' => $this->sort)); ?>"><?php echo Yii::t('wiki', 'Numbered List'); ?></a></li>
    	</ul>
    </li>
  <li class="<?php echo Yii::app()->func->setClassByAction('new', 'current-tab'); ?> alignright"><a href="<?php echo $this->createUrl('/wiki/new'); ?>"><?php echo Yii::t('wiki', 'Create Page'); ?></a></li>			
</ul>