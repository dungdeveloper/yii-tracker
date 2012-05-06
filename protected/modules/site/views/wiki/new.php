<?php if(isset($_POST['preview'])): ?>
    <?php echo $this->renderPartial('_preview', array('content' => $revisionModel->content, 'title' => $model->title)); ?>
<?php endif; ?>

<div id="project-manager" class="tabber"> 
	<div class="tabber-navigation"> 
		<?php echo $this->renderPartial('_wikimenu'); ?>
	</div> 					
	<div id="create-ticket" class="panel">
					
		<div class="entry inner">
			<p><?php echo Yii::t('wiki', 'Enter a name and a short description for the new wiki you are about to create.'); ?></p>
		</div>
				
		<div id="respond">					
            <?php echo $this->renderPartial('_pageform', array('model' => $model, 'revisionModel' => $revisionModel)); ?>										
		</div>				
	</div>
</div>