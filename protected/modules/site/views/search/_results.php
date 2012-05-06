<p id="searchresults">
    <?php if(count($tickets)): ?>
        <span class="category"><?php echo Yii::t('tickets', 'Tickets'); ?></span>
        <?php foreach($tickets as $ticket): ?>
            <?php echo $ticket->getLink('<span class="searchheading">'.substr(CHtml::encode($ticket->title), 0, 30).'</span><span>'.Yii::t('tickets', 'Status: {status}, Comments: {comments}', array('{comments}' => $ticket->commentsCount, '{status}' => $ticket->status ? $ticket->status->title : Yii::t('tickets', 'None'))).'</span>'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <span class="category"><?php echo Yii::t('search', 'No results found.'); ?></span>
    <?php endif; ?> 
    <span class="seperator"><?php echo CHtml::link(Yii::t('tickets', 'Advanced Search'), array('/tickets/search'), array('title' => Yii::t('tickets', 'Use the advanced search feature to find the tickets you are looking for.'))); ?></span>  
</p>