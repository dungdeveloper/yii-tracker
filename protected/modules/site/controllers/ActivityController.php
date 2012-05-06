<?php

/**
 * Activity controller
 */
class ActivityController extends SiteBaseController {

    /**
     * Controller constructor
     */
    public function init() {
        parent::init();
    }

    /**
     * Index action
     */
    public function actionIndex() {
        $this->render('index', array('activities' => Activity::model()->getMyActivity()));
    }

}