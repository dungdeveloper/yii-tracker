<?php if(count($projects)): ?>
   <?php foreach($projects as $project): ?>
    <ol class="ticket-list">
            <li id='projectid-<?php echo $project->id; ?>' class="ticket<?php echo Yii::app()->func->alternateCss(' alt', ''); ?>">
                <ul class="ticket-meta project-meta">
                    <li class='project-title'><small><?php echo Yii::t('project', 'Project Name'); ?></small><a href="<?php echo $this->createUrl('/project/' . $project->id . '/' . $project->alias); ?>" title="<?php echo Yii::t('projects', 'View Project'); ?>"><?php echo CHtml::encode($project->title); ?></a></li>
                    <li><small><?php echo Yii::t('global', 'Created'); ?></small><?php echo Yii::app()->dateFormatter->formatDateTime($project->created); ?></li>
                    <li class='project-options'>
                        <span><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/edit.png', 'edit'), array('/projects/edit', 'id' => $project->id), array('title' => Yii::t('projects', 'Edit project information'))); ?></span>
                        <span id='projectspan-<?php echo $project->id; ?>' class='__changeStatus'><?php echo CHtml::link(CHtml::image(Yii::app()->params['imagesUrl'] . '/icons/' . ($project->status ? 'x.png' : 'v.png') , 'status'), 'javascript:', array('title' => Yii::t('projects', ($project->status ? 'Archive Project!' : 'Set Project as active!') ))); ?></span>
                    </li>
                </ul>
                <?php if($project->description): ?><p class="project-description"><?php echo CHtml::encode($project->description); ?></p><?php endif; ?>
            </li>                
        </ol>
    <?php endforeach; ?>
<?php else: ?>
    <div class="entry inner">
		<p><?php echo Yii::t('projects', 'There are no projects to display.'); ?></p>
	</div>   
<?php endif; ?>  