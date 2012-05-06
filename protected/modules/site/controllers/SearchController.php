<?php

/**
 * Search controller Home page
 */
class SearchController extends SiteBaseController {

    /**
     * Controller constructor
     */
    public function init() {
        parent::init();
    }

    /**
     * Index action
     * -----------------
     */
    public function actionSuggestion($term) {
        // Find tickets that match that term
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.title', $term);
        $criteria->limit = 10;
        $criteria->order = 't.title ASC';

        $tickets = Tickets::model()->with(array('status', 'commentsCount'))->findAll($criteria);
        echo $this->renderPartial('_results', array('tickets' => $tickets), true);
        exit;
    }

}