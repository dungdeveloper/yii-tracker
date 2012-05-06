<div class="tabber">
	<div class="tabber-navigation"> 
		<ul>
          <li class="current-tab"><a name='title'><?php echo CHtml::encode($title); ?></a></li>
        </ul>
	</div> 				
	<div class="panel entry inner">
	       <?php $this->beginWidget('CHtmlPurifier'); ?>
	           <?php echo $content; ?> 
	       <?php $this->endWidget(); ?>
	</div>
    <div class="tabber-navigation bottom">
        <ul>
            <li class="alignright"></li>
        </ul>
    </div>
</div>