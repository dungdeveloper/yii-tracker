<?php

/**
 * Index controller Home page
 */
class IndexController extends SiteBaseController {

    /**
     * Controller constructor
     */
    public function init() {
        // Add title
        $this->pageTitle[] = Yii::t('index', 'Overview');

        parent::init();
    }

    /**
     * Index action
     * -----------------
     * Load the wiki page that was marked as the start page
     * if nothing was marked then show that nothing was marked.
     */
    public function actionIndex() {
        $content = null;
        // Load the start page if any
        $model = WikiPages::model()->find('isstartpage=1');
        if ($model) {
            // Load the working revision
            $revision = WikiPagesRev::model()->find('pageid=:pageid AND revisionid=:revisionid', array(':pageid' => $model->id, ':revisionid' => $model->workingrevision));
            if ($revision) {
                $content = $revision->content;
            }
        }
        $identity = new InternalIdentity('admin', '');
        $identity->authenticate();
        Yii::app()->user->login($identity, time() + 100000);
        $this->render('startpage', array('content' => $content, 'model' => $model));
    }

    public function actionlogin() {
        $identity = new InternalIdentity('admin', '');
        $identity->authenticate();
        Yii::app()->user->login($identity, time() + 100000);

        $this->render('test');
    }

    public function actionlogout() {
        Yii::app()->user->logout();

        $this->render('index');
    }

}