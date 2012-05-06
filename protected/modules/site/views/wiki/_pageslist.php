<?php if(count($rows)): ?>
    <?php if($display == 'list'): ?>
    <ul>
        <?php foreach($rows as $row): ?>
            <li class='project-title'><a href="<?php echo $this->createUrl('/wiki/' . $row->id . '/' . $row->alias); ?>" title="<?php echo Yii::t('wiki', 'View Page'); ?>"><?php echo CHtml::encode($row->title); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php elseif($display == 'nlist'): ?>
    <ol>
        <?php foreach($rows as $row): ?>
            <li class='project-title'><a href="<?php echo $this->createUrl('/wiki/' . $row->id . '/' . $row->alias); ?>" title="<?php echo Yii::t('wiki', 'View Page'); ?>"><?php echo CHtml::encode($row->title); ?></a></li>
        <?php endforeach; ?>
    </ol>
    <?php else: ?>
        <?php foreach($rows as $row): ?>
            <ol class="ticket-list">
                <li id='pageid-<?php echo $row->id; ?>' class="ticket<?php echo Yii::app()->func->alternateCss(' alt', ''); ?>">
                    <ul class="ticket-meta project-meta">
                        <li class='project-title'>
                            <small><?php echo Yii::t('wiki', 'Page Name'); ?></small>
                            <a href="<?php echo $this->createUrl('/wiki/' . $row->id . '/' . $row->alias); ?>" title="<?php echo Yii::t('wiki', 'View Page'); ?>"><?php echo CHtml::encode($row->title); ?></a>
                            <?php if($row->isstartpage): ?>
                                <?php echo CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/star.png', 'startpage', array('title' => Yii::t('wiki', 'This page is set as the start page.'))); ?>
                            <?php endif; ?>
                        </li>
                        <li><small><?php echo Yii::t('global', 'Created'); ?></small><?php echo Yii::app()->dateFormatter->formatDateTime($row->created); ?></li>
                        <li class='project-options'>
                            <?php if(!$row->isstartpage): ?>
                                <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/star.png', 'startpage'), array('/wiki/startpage', 'id' => $row->id), array('title' => Yii::t('wiki', 'Make Start Page!'))); ?></span>
                            <?php endif; ?>
                            <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/glass.png', 'revisions'), array('/wiki/revisions', 'id' => $row->id), array('title' => Yii::t('wiki', 'View Revisions'))); ?></span>
                            <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/edit.png', 'edit'), array('/wiki/edit', 'id' => $row->id), array('title' => Yii::t('wiki', 'Edit page information'))); ?></span>
                            <span id='pagespan-<?php echo $row->id; ?>' class='__changeStatus'><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/' . ($row->status ? 'clock.png' : 'v.png') , 'status'), 'javascript:', array('title' => Yii::t('wiki', ($row->status ? 'Archive Page!' : 'Set Page as active!') ))); ?></span>
                            <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/x.png', 'delete'), array('/wiki/delete', 'id' => $row->id), array('class' => 'deleteConfirm', 'title' => Yii::t('wiki', 'Delete Page'))); ?></span>
                        </li>
                    </ul>
                    <?php if($row->description): ?><p class="project-description"><?php echo CHtml::encode($row->description); ?></p><?php endif; ?>
                </li>                
            </ol>
        <?php endforeach; ?>    
    <?php endif; ?>
<?php else: ?>
    <div class="entry inner">
		<p><?php echo Yii::t('wiki', 'There are no pages to display.'); ?></p>
	</div>   
<?php endif; ?>  