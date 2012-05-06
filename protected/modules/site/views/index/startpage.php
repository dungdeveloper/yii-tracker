<div class="tabber">				
	<?php if(isset($content) && $content): ?>
	   <?php $this->beginWidget('CHtmlPurifier'); ?>
	           <?php echo $content; ?> 
	   <?php $this->endWidget(); ?>
	   <div class="bottom"><?php echo CHtml::link(Yii::t('index', 'Edit This Page'), array('/wiki/edit', 'id' => $model->id)); ?></div>
	<?php else: ?>
	   <h1><?php echo Yii::t('index', 'No Start Page'); ?></h1>
	   <p>
	       <?php echo Yii::t('index', 'In order to display content here you need to create a new wiki page and set it as the start page. You can do that by visiting the {wiki} section.', array('{wiki}' => CHtml::link(Yii::t('global', 'Wiki'), array('/wiki')))); ?>
	   </p>
	<?php endif; ?>
</div>