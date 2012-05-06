<?php

/**
 * error controller Home page
 */
class ErrorController extends SiteBaseController {

    public function init() {
        parent::init();

        $this->breadcrumbs[Yii::t('error', 'Error')] = array('index/index');
        $this->pageTitle[] = Yii::t('error', 'Error');
    }

    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        $this->render('error', array('error' => $error));
    }

}