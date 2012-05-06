<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml" lang="<?php echo Yii::app()->language; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset; ?>">
        <title><?php echo ( count( $this->pageTitle ) ) ? implode( ' - ', array_reverse( $this->pageTitle ) ) : $this->pageTitle; ?></title>

		<link href="" type="image/x-icon" rel="icon">
		<link href="" type="image/x-icon" rel="shortcut icon">
		
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/jquery.custom.js', CClientScript::POS_HEAD ); ?>
		<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/jquery.tipsy.js', CClientScript::POS_END ); ?>
		
		<?php Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style.css', 'screen' ); ?>
</head>
<body class="home blog">

	<div id="container">
	
		<div id="top"><!-- #top -->
	       <div>
			 <ul>
			     <li class="page_item"><a href="<?php echo $this->createUrl('/'); ?>"><?php echo Yii::t('global', 'Overview'); ?></a></li>
			     <li class="page_item"><a href="<?php echo $this->createUrl('/projects'); ?>"><?php echo Yii::t('global', 'Projects'); ?></a></li>
			     <li class="page_item"><a href="<?php echo $this->createUrl('/activity'); ?>"><?php echo Yii::t('global', 'Activity'); ?></a></li>
			     <li class="page_item"><a href="<?php echo $this->createUrl('/roadmap'); ?>"><?php echo Yii::t('global', 'Roadmap'); ?></a></li>
			     <li class="page_item"><a href="<?php echo $this->createUrl('/tickets'); ?>"><?php echo Yii::t('global', 'Tickets'); ?></a></li>
			     <li class="page_item"><a href="<?php echo $this->createUrl('/wiki'); ?>"><?php echo Yii::t('global', 'Wiki'); ?></a></li>
			 </ul>
        </div>
			
        <div id="logged-in">
            <a href="<?php echo $this->createUrl('/login'); ?>"><?php echo Yii::t('global', 'Login'); ?></a> &middot;
            <a href="<?php echo $this->createUrl('/signup'); ?>"><?php echo Yii::t('global', 'Signup'); ?></a>	&middot;
            <a href="<?php echo $this->createUrl('/admin'); ?>"><?php echo Yii::t('global', 'Admin'); ?></a>			
        </div>
		</div><!-- End #top -->
 
		<div class='header-space'><!-- Space --></div>

		<?php if(Yii::app()->user->hasFlash('message')): ?>
		  <div id='message'><?php echo Yii::app()->user->getFlash('message'); ?></div>
		<?php endif; ?>
				
		<div id="content"> 
			<div id="main<?php if(Yii::app()->getController()->id != 'tickets'): ?>-large<?php endif; ?>" role="main">
                <?php echo $content; ?>
            </div><!-- End #main -->
			<?php if(Yii::app()->getController()->id == 'tickets'): ?>
			<div id="sidebar" class="widget-area" role="complementary"> 
	           <?php $this->widget('widgets.search.search'); ?>
				<ul class="submenu">
					<li class="widget-container">
					   <?php echo CHtml::beginForm(array('/tickets/search'), 'post', array('id' => 'searchform', 'role' => 'search')); ?>
	                       <div>
	                           <label class="screen-reader-text" for="search"><?php echo Yii::t('global', 'Search for:'); ?></label>
	                           <input type="text" value="" name="SearchTickets[title]" id="search" autocomplete="off">
	                           <?php echo CHtml::submitButton(Yii::t('global', 'Search'), array('id' => 'searchsubmit')); ?>
	                       </div>
	                       <div id="suggestions"></div>
	                   <?php echo CHtml::endForm(); ?>
                    </li>
                    <li class="widget-container">
                        <h3><?php echo Yii::t('global', 'Statuses'); ?></h3>			
                            <ul>
                                <?php foreach(TicketStatus::model()->with(array('ticketsCount'))->findAll() as $status): ?>
    							<li>
                                    <?php echo $status->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $status->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $status->getLink(CHtml::encode($status->title) . ' <small>('.Yii::app()->format->number($status->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul> 
		              </li>
		              <li class="widget-container">
                        <h3><?php echo Yii::t('global', 'Priorities'); ?></h3>			
                            <ul>
                                <?php foreach(TicketPriority::model()->with(array('ticketsCount'))->findAll() as $priority): ?>
    							<li>
                                    <?php echo $priority->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $priority->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $priority->getLink(CHtml::encode($priority->title) . ' <small>('.Yii::app()->format->number($priority->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul> 
		              </li>
		              <li class="widget-container">
                        <h3><?php echo Yii::t('global', 'Types'); ?></h3>			
                            <ul>
                                <?php foreach(TicketType::model()->with(array('ticketsCount'))->findAll() as $type): ?>
    							<li>
                                    <?php echo $type->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $type->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $type->getLink(CHtml::encode($type->title) . ' <small>('.Yii::app()->format->number($type->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul> 
		              </li>
		              <li class="widget-container">
		                  <h3><?php echo Yii::t('global', 'Milestones'); ?></h3>
		                      <ul>
								<?php foreach(Milestones::model()->with(array('ticketsCount'))->findAll() as $milestone): ?>
    							<li>
                                    <?php echo $milestone->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $milestone->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $milestone->getLink(CHtml::encode($milestone->title) . ' <small>('.Yii::app()->format->number($milestone->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul> 
		              </li>
		              <li class="widget-container">
		                  <h3><?php echo Yii::t('global', 'Categories'); ?></h3>
		                      <ul>
								<?php foreach(TicketCategory::model()->with(array('ticketsCount'))->findAll() as $category): ?>
    							<li>
                                    <?php echo $category->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $category->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $category->getLink(CHtml::encode($category->title) . ' <small>('.Yii::app()->format->number($category->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul>
		              </li>	
		              <li class="widget-container">
		                  <h3><?php echo Yii::t('global', 'Versions'); ?></h3>
		                      <ul>
								<?php foreach(TicketVersion::model()->with(array('ticketsCount'))->findAll() as $version): ?>
    							<li>
                                    <?php echo $version->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $version->getRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $version->getVersionLink(CHtml::encode($version->title) . ' <small>('.Yii::app()->format->number($version->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul> 
		              </li>		
		               <li class="widget-container">
		                  <h3><?php echo Yii::t('global', 'Fixed In'); ?></h3>
		                      <ul>
								<?php foreach(TicketVersion::model()->with(array('ticketsCount'))->findAll() as $fixedin): ?>
    							<li>
                                    <?php echo $fixedin->getFixedRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/atom.gif', 'ATOM'), array('class' => 'atom'), 'atom' ); ?>
                                    <?php echo $fixedin->getFixedRssLink( CHtml::image(Yii::app()->themeManager->baseUrl.'/images/rss.gif', 'RSS'), array('class' => 'rss') ); ?>
                                    <?php echo $fixedin->getFixedinLink(CHtml::encode($fixedin->title) . ' <small>('.Yii::app()->format->number($fixedin->ticketsCount).')</small>'); ?>
                                </li>
                                <?php endforeach; ?>
							</ul> 
		              </li>		
				</ul> 
	 
			</div><!-- End #sidebar -->
			<?php endif; ?>		
		</div><!-- End #content --> 
		
		<div id="footer">
			<ul>
				<li><?php echo Yii::t('global', 'Powered By {x}', array('{x}' => CHtml::link('X', '#'))); ?></li>
				
				<li class="alignright"><?php echo Yii::t('global', 'Page generated in {time} seconds', array('{time}' => round(CLogger::getExecutionTime(), 3))); ?></li>
			</ul>
		</div>
	
	</div><!-- End #container --> 
<?php 
    $settings = array(
                'baseUrl' => Yii::app()->baseUrl,
                'currentUrl' => $this->createUrl(''),
                'debugMode' => (int) YII_DEBUG,
    );
    $phrases = array_merge($this->jsLanguages, array(
                'cancel' => Yii::t('global', 'Cancel'),
                'deleteConfirm' => Yii::t('global', 'Are you sure you would like to delete this item?'),
                'inlineEditButton' => Yii::t('global', 'Update'),
    )); 
    $phrases = 'Tracker.phrases = ' . CJSON::encode($phrases) . ';';
    $settings = 'Tracker.settings = ' . CJSON::encode($settings) . ';';
    Yii::app()->clientScript->registerScript('_sitesettings', $settings, CClientScript::POS_END);
    Yii::app()->clientScript->registerScript('_sitephrases', $phrases, CClientScript::POS_END);
?>
</body>
</html>