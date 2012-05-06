<div class="tabber">
	<?php echo $this->renderPartial('_pagemenu', array('model' => $model, 'title' => $title, 'showViewTab' => true)); ?>			
	<div class="panel entry inner">
	      <?php if($revisions): ?>
	       <?php echo CHtml::beginForm('', 'post', array('id' => 'revisionDiffForm')); ?>
            <?php foreach($revisions as $revision): ?>
                <div class='revision-row'>
                    <span><?php echo CHtml::radioButton('revisionFrom', false, array('value' => $revision->id)); ?></span>
                    <span><?php echo CHtml::radioButton('revisionTo', false, array('value' => $revision->id)); ?></span>
                    <span><?php echo WikiPages::model()->getModelRevisionLink($revision->revisionid, $model->id, $model->alias, array('title' => Yii::t('wiki', 'Logged on {date} By {user}', array('{date}' => Yii::app()->dateFormatter->formatDateTime($revision->created, 'short', 'short'), '{user}' => $revision->author->username)))); ?></span>
                    <span>
                        &nbsp; <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/x.png', 'delete'), array('/wiki/deleterevision', 'id' => $revision->id), array('class' => 'deleteConfirm', 'title' => Yii::t('wiki', 'Delete Revision'))); ?></span>
                        &nbsp; <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/a_left.png', 'restore'), array('/wiki/restorerevision', 'id' => $revision->id), array('title' => Yii::t('wiki', 'Restore Revision'))); ?></span>
                    </span>
                </div>
                <?php if($revision->comment): ?>
                    <div class='revision-comment'>
                        <strong><?php echo Yii::t('wiki', 'Comment:'); ?></strong> <?php echo CHtml::encode($revision->comment); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <div class='revision-diff-button'><?php echo CHtml::submitButton(Yii::t('wiki', 'Show Changes')); ?></div>
            <?php echo CHtml::endForm(); ?>
	      <?php else: ?>
            <div>
                <span><?php echo Yii::t('wiki', 'There are no revisions for this page.'); ?></span>
            </div>
	      <?php endif; ?>
	</div>
    <div class="tabber-navigation bottom">
        <ul>
            <li class="alignright"></li>
        </ul>
    </div><!-- #nav-above -->
</div><!-- End #ticket-manager --> 
<?php $this->widget('widgets.fancybox.fancybox'); ?>