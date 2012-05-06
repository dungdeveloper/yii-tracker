<?php

/**
 * Tickets controller Home page
 */
class TicketsController extends SiteBaseController {

    /**
     * Controller constructor
     */
    public function init() {
        // Add in the required js
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/public/scripts/tickets.js', CClientScript::POS_END);

        // Add title
        $this->pageTitle[] = Yii::t('tickets', 'Tickets');

        parent::init();
    }

    /**
     * Get list of usernames for the assigned to field
     * @param string $term
     * @return json object
     */
    public function actionGetUserNames($term) {
        // Find usernames that match $term
        if (trim($term)) {
            // Initiate Criteria
            $criteria = new CDbCriteria;
            $criteria->addSearchCondition('username', trim($term));
            $found = Users::model()->findAll($criteria);
            $return = array();
            foreach ($found as $user) {
                $return[] = $user->username;
            }
            echo CJSON::encode($return);
        }
        Yii::app()->end();
    }

    /**
     * Index action
     */
    public function actionIndex() {
        $criteria = new CDbCriteria;
        // Initiate Pager
        $count = Tickets::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = Yii::app()->params['ticketsPerPage'];
        $pages->route = '/tickets/index';
        $pages->applyLimit($criteria);

        // Load them
        $tickets = Tickets::model()->with(array('reporter', 'assigned', 'status', 'type', 'category', 'ticketpriority', 'version', 'fixed', 'ticketmilestone', 'lastcomment', 'commentsCount'))->byLastCommentDate()->findAll($criteria);

        // Load the quick moderation form
        $moderation = new TicketsQuickModeration;

        $this->render('ticketslist', array('total' => $count, 'tickets' => $tickets, 'pages' => $pages, 'moderation' => $moderation));
    }

    /**
     * View single ticket
     * 
     * @param int $id
     * @param string $alias
     */
    public function actionViewTicket($id, $alias) {
        if ($id && ($ticket = Tickets::model()->with(array('reporter', 'assigned', 'status', 'type', 'category', 'ticketpriority', 'version', 'fixed', 'ticketmilestone', 'comments', 'comments.commentreporter', 'comments.changes', 'lastcomment'))->byCommentDate()->findByPk($id))) {
            // Initiate the ticket comment model
            $comment = new TicketComments;
            $props = new TicketProps;
            $ticketChanges = array();
            $commentPassed = false;
            $propsPassed = false;
            $propertiesPassed = false;
            // Make sure if we submit the form that we enter a comment
            if (isset($_POST['TicketComments'])) {
                $comment->setAttributes($_POST['TicketComments']);
                $comment->ticketid = $ticket->id;
                if ($comment->validate()) {
                    $commentPassed = true;
                }
            }

            // Make suer we submitted the form
            if (isset($_POST['TicketProps'])) {
                $props->setAttributes($_POST['TicketProps']);
                if ($props->validate()) {
                    $propsPassed = true;

                    // did we change the title
                    if ($props->title != $ticket->title) {
                        $ticketChanges[] = Yii::t('tickets', '<strong>Title</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $ticket->title, '{new}' => $props->title));
                    }

                    // did we change the keywords
                    if ($props->keywords != $ticket->keywords) {
                        $ticketChanges[] = Yii::t('tickets', '<strong>Keywords</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $ticket->keywords, '{new}' => $props->keywords));
                    }

                    // did we change the assigned to
                    if ($props->assignedtoid && $props->assignedtoid != $ticket->assignedtoid) {
                        // Load the users
                        $oldUser = Users::model()->findByPk($ticket->assignedtoid);
                        $newUser = Users::model()->find('username=:username', array(':username' => $props->assignedtoid));
                        $ticketChanges[] = Yii::t('tickets', '<strong>Assigned</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldUser ? $oldUser->getLink() : "''", '{new}' => $newUser ? $newUser->getLink() : "''"));
                    }

                    // Did we change the status
                    if ($props->status && $props->status != $ticket->ticketstatus) {
                        $oldStatus = TicketStatus::model()->findByPk($ticket->ticketstatus);
                        $newStatus = TicketStatus::model()->findByPk($props->status);
                        $ticketChanges[] = Yii::t('tickets', '<strong>Status</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldStatus ? $oldStatus->getLink() : "''", '{new}' => $newStatus ? $newStatus->getLink() : "''"));
                        $ticket->ticketstatus = $props->status;
                    }

                    // Did we change the type
                    if ($props->type && $props->type != $ticket->tickettype) {
                        $oldType = TicketType::model()->findByPk($ticket->tickettype);
                        $newType = TicketType::model()->findByPk($props->type);
                        $ticketChanges[] = Yii::t('tickets', '<strong>Type</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldType ? $oldType->getLink() : "''", '{new}' => $newType ? $newType->getLink() : "''"));
                        $ticket->tickettype = $props->type;
                    }

                    // Did we change the category
                    if ($props->category && $props->category != $ticket->ticketcategory) {
                        $oldCategory = TicketCategory::model()->findByPk($ticket->ticketcategory);
                        $newCategory = TicketCategory::model()->findByPk($props->category);
                        $ticketChanges[] = Yii::t('tickets', '<strong>Category</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldCategory ? $oldCategory->getLink() : "''", '{new}' => $newCategory ? $newCategory->getLink() : "''"));
                        $ticket->ticketcategory = $props->category;
                    }

                    // Did we change the version
                    if ($props->version && $props->version != $ticket->ticketversion) {
                        $oldVersion = TicketVersion::model()->findByPk($ticket->ticketversion);
                        $newVersion = TicketVersion::model()->findByPk($props->version);
                        $ticketChanges[] = Yii::t('tickets', '<strong>Version</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldVersion ? $oldVersion->getVersionLink() : "''", '{new}' => $newVersion ? $newVersion->getVersionLink() : "''"));
                        $ticket->ticketversion = $props->version;
                    }

                    // Did we change the fixedin
                    if ($props->fixedin && $props->fixedin != $ticket->fixedin) {
                        $oldFixed = TicketVersion::model()->findByPk($ticket->fixedin);
                        $newFixed = TicketVersion::model()->findByPk($props->fixedin);
                        $ticketChanges[] = Yii::t('tickets', '<strong>Fixed In Version</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldFixed ? $oldFixed->getFixedinLink() : "''", '{new}' => $newFixed ? $newFixed->getFixedinLink() : "''"));
                        $ticket->fixedin = $props->fixedin;
                    }

                    // Did we change the priority
                    if ($props->priority && $props->priority != $ticket->priority) {
                        $oldPriority = TicketPriority::model()->findByPk($ticket->priority);
                        $newPriority = TicketPriority::model()->findByPk($props->priority);
                        $ticketChanges[] = Yii::t('tickets', '<strong>Priority</strong> Changed from <em>{old}</em> To <em>{new}</em>', array('{old}' => $oldPriority ? $oldPriority->getLink() : "''", '{new}' => $newPriority ? $newPriority->getLink() : "''"));
                        $ticket->priority = $props->priority;
                    }

                    // Assign props data to ticket
                    $ticket->title = $props->title;
                    $ticket->keywords = $props->keywords;

                    // Reset the ticket assigned to
                    if ($props->assignedtoid) {
                        $ticket->assignedtoid = $props->assignedtoid;
                    } else {
                        $ticket->assignedtoid = (int) $ticket->assignedtoid;
                    }

                    // Make sure we have some changes or at least entered comment
                    if (!$comment->content && !count($ticketChanges)) {
                        $commentPassed = false;
                        Functions::setFlash(Yii::t('tickets', 'You must enter a comment or make some changes to the ticket.'));
                    }

                    // validate ticket
                    if ($ticket->validate()) {
                        $propertiesPassed = true;
                    }
                }
            }

            // Only if all flags were ok then save and update
            if (isset($_POST['submit']) && $commentPassed && $propsPassed && $propertiesPassed) {
                // Save both and redirect
                $comment->save();

                // Did we change something?
                if (count($ticketChanges)) {
                    foreach ($ticketChanges as $change) {
                        $changes = new TicketChanges;
                        $changes->ticketid = $ticket->id;
                        $changes->commentid = $comment->id;
                        $changes->content = $change;
                        $changes->save();
                    }
                }

                // Save ticket
                $ticket->lastcommentid = $comment->id;
                $ticket->update();

                // Set flash
                Functions::setFlash(Yii::t('tickets', 'Tickets: Ticket Updated'));
                $this->redirect($ticket->getLink('', array(), true));
            }

            // Reset the ticket assigned to
            if ($props->assignedtoid) {
                if (is_int($props->assignedtoid)) {
                    $username = Users::model()->findByPk($props->assignedtoid);
                } else {
                    $username = Users::model()->find('username=:username', array(':username' => $props->assignedtoid));
                }
                $props->assignedtoid = $username ? $username->username : '';
            } else {
                $props->assignedtoid = '';
            }

            // Assign keywords
            if (!$props->keywords && $ticket->keywords) {
                $props->keywords = $ticket->keywords;
            }

            // Assign Title
            if (!$props->title && $ticket->title) {
                $props->title = $ticket->title;
            }

            // Show the view screen
            $this->pageTitle[] = Yii::t('tickets', 'Viewing Ticket - {title}', array('{title}' => CHtml::encode($ticket->title)));
            $this->render('showticket', array('props' => $props, 'ticket' => $ticket, 'comment' => $comment));
        } else {
            $this->redirect(array('/tickets'));
        }
    }

    /**
     * Quick moderation action handler
     *
     */
    public function actionQuickModeration() {
        // Load the quick moderation form
        $moderation = new TicketsQuickModeration;
        if (isset($_POST['quickModeration']) && isset($_POST['TicketsQuickModeration'])) {
            //Make sure we selected ids
            if (isset($_POST['tickets']) && count($_POST['tickets'])) {
                $moderation->ids = $_POST['tickets'];
                $moderation->setAttributes($_POST['TicketsQuickModeration']);
                if ($moderation->validate()) {
                    // Mark flash and redirect
                    Functions::setFlash(Yii::t('tickets', 'Tickets: {n} Tickets Updated.', array('{n}' => count($_POST['tickets']))));
                    $this->redirect(Yii::app()->request->getUrlReferrer());
                }
            } else {
                // Mark flash and redirect
                Functions::setFlash(Yii::t('tickets', 'Tickets: Please select at least on ticket.'));
            }
        }
        // In case we have nothing
        $this->redirect(array('/tickets'));
    }

    /**
     * Create a new ticket
     */
    public function actionCreate() {
        // Initiate Model
        $model = new Tickets;
        $model->assignedtoid = '';
        if (isset($_POST['Tickets'])) {
            $model->setScenario('ticketupdate');
            $model->setAttributes($_POST['Tickets']);
            // Was the form submitted?
            if (isset($_POST['submit'])) {
                // Bug: assigntoid cannot be null
                if (!$model->assignedtoid) {
                    $model->assignedtoid = 0;
                }
                if ($model->save()) {
                    $comment = new TicketComments;
                    $comment->content = $model->content;
                    $comment->ticketid = $model->id;
                    $comment->firstcomment = 1;
                    $comment->save();

                    // Update ticket id
                    $model->lastcommentid = $comment->id;
                    $model->update();

                    // Mark flash and redirect
                    Functions::setFlash(Yii::t('tickets', 'Tickets: Ticket Created.'));
                    $this->redirect(array('/tickets'));
                }
            }
        }

        // make sure we display the name of the 
        if (isset($_POST['Tickets']['assignedtoid'])) {
            $model->assignedtoid = $_POST['Tickets']['assignedtoid'];
        }

        // Add title
        $title = Yii::t('tickets', 'Creating A Ticket');
        $this->pageTitle[] = $title;
        $this->render('create', array('model' => $model, 'title' => $title));
    }

    /**
     * Update a ticket
     */
    public function actionEdit() {
        if (Yii::app()->request->getParam('id') && ($model = Tickets::model()->findByPk(Yii::app()->request->getParam('id')))) {
            // Form Submited
            if (isset($_POST['Tickets'])) {
                $model->setScenario('ticketupdate');
                $model->setAttributes($_POST['Tickets']);
                // Was the form submitted?
                if (isset($_POST['submit'])) {
                    if ($model->save()) {
                        // Mark flash and redirect
                        Functions::setFlash(Yii::t('tickets', 'Tickets: Ticket Updated.'));
                        $this->redirect(array('/tickets'));
                    }
                }
            }
        }

        // Add title
        $title = Yii::t('tickets', 'Updating Ticket');
        $this->pageTitle[] = $title;
        $this->render('create', array('model' => $model, 'title' => $title));
    }

    /**
     * Filter tickets by certain condition
     *
     */
    public function actionFilterIssues($type, $id, $alias, $rss=null) {
        // Initiate Criteria
        $criteria = new CDbCriteria;
        $pages = new CPagination();
        $pages->route = '/issues/' . $type . '/' . $id . '/' . $alias;
        switch ($type) {
            case 'status':
                $criteria->condition = 't.ticketstatus=:status';
                $criteria->params = array(':status' => $id);
                break;
            case 'type':
                $criteria->condition = 't.tickettype=:type';
                $criteria->params = array(':type' => $id);
                break;
            case 'priority':
                $criteria->condition = 't.priority=:priority';
                $criteria->params = array(':priority' => $id);
                break;
            case 'category':
                $criteria->condition = 't.ticketcategory=:category';
                $criteria->params = array(':category' => $id);
                break;
            case 'version':
                $criteria->condition = 't.ticketversion=:version';
                $criteria->params = array(':version' => $id);
                break;
            case 'fixedin':
                $criteria->condition = 't.fixedin=:fixedin';
                $criteria->params = array(':fixedin' => $id);
                break;
            case 'milestone':
                $criteria->condition = 't.milestone=:milestone';
                $criteria->params = array(':milestone' => $id);
                break;
            default:
                $this->redirect(array('/tickets'));
                break;
        }

        // Initiate Pager
        $count = Tickets::model()->count($criteria);

        $pages->pageSize = Yii::app()->params['ticketsPerPage'];
        $pages->applyLimit($criteria);

        // Load them
        $tickets = Tickets::model()->with(array('reporter', 'assigned', 'status', 'type', 'category', 'ticketpriority', 'version', 'fixed', 'ticketmilestone'))->byDate()->findAll($criteria);

        // Did we wanted to see the rss
        if ($rss && in_array($rss, array('rss', 'atom'))) {
            // Load the feed writer
            Yii::import('ext.FeedWriter.FeedWriter');
            $feedWriter = new FeedWriter($rss == 'atom' ? ATOM : RSS2);

            $channelElems = array(
                'title' => Yii::t('tickets', 'Tickets Feed'),
                'link' => Yii::app()->createAbsoluteUrl('/tickets'),
                'charset' => Yii::app()->charset,
                'description' => Yii::t('tickets', 'Tickets Feed'),
                'author' => Yii::app()->name,
                'generator' => Yii::app()->name,
                'language' => Yii::app()->language,
                'ttl' => 10,
            );

            // Set channel elements
            $feedWriter->setChannelElementsFromArray($channelElems);

            if ($tickets) {
                foreach ($tickets as $r) {
                    $newItem = $feedWriter->createNewItem();
                    $itemElems = array(
                        'title' => htmlspecialchars($r->title),
                        'link' => Yii::app()->createAbsoluteUrl('/issue/' . $r->id . '/' . $r->alias),
                        'charset' => Yii::app()->charset,
                        'description' => htmlspecialchars(substr(strip_tags($r->content), 0, 100)),
                        'author' => $r->reporter ? $r->reporter->username : Yii::app()->name,
                        'generator' => Yii::app()->name,
                        'language' => Yii::app()->language,
                        'guid' => $r->id,
                        'content' => htmlspecialchars($r->content),
                    );

                    $newItem->addElementArray($itemElems);
                    //Now add the feed item
                    $feedWriter->addItem($newItem);
                }
            }

            // Display & end
            echo $feedWriter->genarateFeed();
            exit;
        }

        // Load the quick moderation form
        $moderation = new TicketsQuickModeration;

        // Title
        $this->pageTitle[] = Yii::t('tickets', 'Viewing Issues');

        // Render
        $this->render('ticketslist', array('total' => $count, 'tickets' => $tickets, 'pages' => $pages, 'moderation' => $moderation));
    }

    /**
     * Filter tickets by tag
     *
     */
    public function actionFilterIssuesByTag($tag) {
        // Initiate Criteria
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.keywords', $tag);

        // Initiate Pager
        $pages = new CPagination();
        $pages->route = '/issues/tag/' . $tag;
        $count = Tickets::model()->count($criteria);

        $pages->pageSize = Yii::app()->params['ticketsPerPage'];
        $pages->applyLimit($criteria);

        // Load them
        $tickets = Tickets::model()->with(array('reporter', 'assigned', 'status', 'type', 'category', 'ticketpriority', 'version', 'fixed', 'ticketmilestone'))->byDate()->findAll($criteria);

        // Load the quick moderation form
        $moderation = new TicketsQuickModeration;

        // Title
        $this->pageTitle[] = Yii::t('tickets', 'Viewing Issues');

        // Render
        $this->render('ticketslist', array('total' => $count, 'tickets' => $tickets, 'pages' => $pages, 'moderation' => $moderation));
    }

    /**
     * Search issues
     *
     */
    public function actionSearch() {
        $search = new SearchTickets;
        $doSearch = false;
        $tickets = array();
        $pages = array();
        $count = null;

        // If we wanted to reset the form then just reset and redirect back
        if (isset($_POST['reset']) && $_POST['SearchTickets']) {
            if (isset(Yii::app()->session['searchTerms'])) {
                unset(Yii::app()->session['searchTerms']);
            }
            if (Yii::app()->session['didSearch']) {
                unset(Yii::app()->session['didSearch']);
            }
            $this->redirect(array('search'));
        }

        if (isset($_POST['SearchTickets'])) {
            $search->setAttributes($_POST['SearchTickets']);
            if ($search->validate()) {
                $doSearch = true;
                // Store it in the session so pagging will work
                Yii::app()->session['searchTerms'] = $_POST['SearchTickets'];
                Yii::app()->session['didSearch'] = true;
            }
        } elseif (isset(Yii::app()->session['didSearch']) && Yii::app()->session['didSearch'] && isset(Yii::app()->session['searchTerms']) && count(Yii::app()->session['searchTerms'])) {
            $search->setAttributes(Yii::app()->session['searchTerms']);
            if ($search->validate()) {
                $doSearch = true;
                // Store it in the session so pagging will work
                Yii::app()->session['searchTerms'] = Yii::app()->session['searchTerms'];
                Yii::app()->session['didSearch'] = true;
            }
        }

        // If we passed validation then search
        if ($doSearch) {
            // Initiate Criteria
            $criteria = new CDbCriteria;

            // Build criteria
            if ($search->title) {
                $criteria->addSearchCondition('t.title', $search->title);
            }

            if ($search->keywords) {
                $criteria->addSearchCondition('t.keywords', $search->keywords);
            }

            // Start with the properties
            $props = array(
                'status' => 'ticketstatus',
                'type' => 'tickettype',
                'category' => 'ticketcategory',
                'priority' => 'priority',
                'version' => 'ticketversion',
                'fixedin' => 'fixedin',
            );

            // Loop the props and add conditions if they were submitted
            foreach ($props as $key => $value) {
                if ($search->$key) {
                    $criteria->addInCondition('t.' . $value, $search->$key);
                }
            }

            // Add the reported by and assigned to condition
            if ($search->reportedby) {
                $criteria->addInCondition('t.reportedbyid', array($search->reportedby));
            }

            if ($search->assignedtoid) {
                $criteria->addInCondition('t.assignedtoid', array($search->assignedtoid));
            }

            // Initiate Pager
            $count = Tickets::model()->count($criteria);

            $pages = new CPagination($count);
            $pages->route = '/tickets/search';

            $pages->pageSize = Yii::app()->params['ticketsPerPage'];
            $pages->applyLimit($criteria);

            // Load them
            $tickets = Tickets::model()->with(array('reporter', 'assigned', 'status', 'type', 'category', 'ticketpriority', 'version', 'fixed', 'ticketmilestone'))->findAll($criteria);
        }

        // Make sure we display the name and not id of the assigned to
        if ($search->assignedtoid && ($user = Users::model()->findByPk($search->assignedtoid))) {
            $search->assignedtoid = $user->username;
        }

        // Make sure we display the name and not id of the assigned to
        if ($search->reportedby && ($user = Users::model()->findByPk($search->reportedby))) {
            $search->reportedby = $user->username;
        }

        // Load the quick moderation form
        $moderation = new TicketsQuickModeration;

        // Title
        $title = Yii::t('tickets', 'Search Tickets');
        $this->pageTitle[] = $title;

        // Render
        $this->render('search', array('count' => $count, 'moderation' => $moderation, 'pages' => $pages, 'tickets' => $tickets, 'title' => $title, 'search' => $search, 'doSearch' => $doSearch));
    }

}