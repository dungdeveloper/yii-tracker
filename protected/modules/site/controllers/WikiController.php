<?php

/**
 * Wiki controller Home page
 */
class WikiController extends SiteBaseController {

    /**
     * @var string
     */
    public $sort = 'desc';
    public $order = 'created';
    public $validOrder = array('id', 'title', 'userid', 'projectid', 'created');

    /**
     * @var string - display type
     */
    public $display = 'index';
    public $validDisplay = array('index', 'list', 'nlist');

    /**
     * Controller constructor
     */
    public function init() {
        // Add in the required js
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/public/scripts/wiki.js', CClientScript::POS_END);

        // Add title
        $this->pageTitle[] = Yii::t('wiki', 'Wiki');

        // Sort the sort
        if (Yii::app()->request->getParam('sort')) {
            $this->sort = Yii::app()->request->getParam('sort');
            if ($this->sort != 'asc' && $this->sort != 'desc') {
                $this->sort = 'asc';
            }
        }

        // Get order by
        if (Yii::app()->request->getParam('order') && in_array(Yii::app()->request->getParam('order'), $this->validOrder)) {
            $this->order = Yii::app()->request->getParam('order');
        }

        // Display type
        if (Yii::app()->request->getParam('display') && in_array(Yii::app()->request->getParam('display'), $this->validDisplay)) {
            $this->display = Yii::app()->request->getParam('display');
        }

        // Include JS languages
        $this->jsLanguages = array(
            'revisionChooseIds' => Yii::t('wiki', 'Please choose two revisions to compare.'),
            'revisionChooseDiffIds' => Yii::t('wiki', 'Please choose two different revisions to compare with.'),
        );

        parent::init();
    }

    /**
     * Index action
     */
    public function actionIndex() {
        $this->render('index', array('rows' => WikiPages::model()->getActive()->byUser()->orderBy($this->order, $this->sort)->findAll()));
    }

    /**
     * Archived action
     */
    public function actionArchived() {
        $this->render('index', array('rows' => WikiPages::model()->getArchived()->byUser()->orderBy($this->order, $this->sort)->findAll()));
    }

    /**
     * New Wiki Page action
     */
    public function actionNew() {
        $model = new WikiPages;
        $revisionModel = new WikiPagesRev;
        if (isset($_POST['WikiPages'])) {
            $model->setAttributes($_POST['WikiPages']);
            $revisionModel->setAttributes($_POST['WikiPagesRev']);
            // Was the form submitted?
            if (isset($_POST['submit'])) {
                if ($model->validate() && $revisionModel->validate()) {
                    $model->save();
                    $revisionModel->pageid = $model->id;
                    $revisionModel->save();
                    // Update working revision
                    Activity::$toSave = false;
                    $model->workingrevision = $revisionModel->revisionid;
                    $model->update();
                    // Mark flash and redirect
                    Functions::setFlash(Yii::t('wiki', 'Wiki: Page Created.'));
                    $this->redirect(array('/wiki'));
                }
            }
        }
        // Add title
        $this->pageTitle[] = Yii::t('wiki', 'Creating New Wiki Page');
        $this->render('new', array('model' => $model, 'revisionModel' => $revisionModel));
    }

    /**
     * Edit wiki page action
     */
    public function actionEdit() {
        if (Yii::app()->request->getParam('id')
                && ( $model = WikiPages::model()->byUser()->findByPk(Yii::app()->request->getParam('id')) )
                && ($revisionModel = WikiPagesRev::model()->find('pageid=:pageid AND revisionid=:revisionid', array(':pageid' => $model->id, ':revisionid' => $model->workingrevision)))) {
            if (isset($_POST['WikiPages'])) {
                // Add description to the activity
                if ($_POST['WikiPages']['title'] != $model->title) {
                    $model->activity['description'] = 'Changed title from <strong>{old}</strong> to <strong>{new}</strong>';
                    $model->activity['params']['{old}'] = $model->title;
                    $model->activity['params']['{new}'] = $_POST['WikiPages']['title'];
                }

                $newRevisionModel = new WikiPagesRev;
                $model->setAttributes($_POST['WikiPages']);
                $newRevisionModel->setAttributes($_POST['WikiPagesRev']);
                $revisionModel->setAttributes($_POST['WikiPagesRev']);
                // Did we submit the form?
                if (isset($_POST['submit'])) {
                    if ($model->validate() && $newRevisionModel->validate()) {
                        $model->workingrevision++;
                        $model->save();
                        $newRevisionModel->pageid = $model->id;
                        $newRevisionModel->save();
                        // Update working revision
                        Activity::$toSave = false;
                        $model->update();
                        // Flash and redirect
                        Functions::setFlash(Yii::t('wiki', 'Wiki: Page Updated.'));
                        $this->redirect(array('/wiki'));
                    }
                }
            }

            // Blank comment
            $revisionModel->comment = '';

            // Add title
            $this->pageTitle[] = Yii::t('wiki', 'Editing Wiki Page');
            $this->render('edit', array('model' => $model, 'revisionModel' => $revisionModel));
        } else {
            $this->redirect(array('/wiki'));
        }
    }

    /**
     * Change wiki page status action
     */
    public function actionStatus() {
        if (Yii::app()->request->getParam('id') && ( $model = WikiPages::model()->findByPk(Yii::app()->request->getParam('id')) )) {
            // Check it's current status and update
            $currentStatus = $model->status;
            $model->status = $currentStatus == 0 ? 1 : 0;
            // Add description to the activity
            $model->activity['description'] = $model->status ? 'Page Activated.' : 'Page Archived.';
            $model->update();

            Functions::ajaxString($model->status ? Yii::t('wiki', 'Page Activated.') : Yii::t('wiki', 'Page Archived.') );
        } else {
            Functions::ajaxError(Yii::t('error', 'We could not find that page.'));
        }
    }

    /**
     * Show a differences report between two revisions
     *
     */
    public function actionRevisionDiff($revisionFrom, $revisionTo) {
        // Make sure both exists
        $fromObj = WikiPagesRev::model()->findByPk($revisionFrom);
        $toObj = WikiPagesRev::model()->findByPk($revisionTo);
        if (!$fromObj || !$toObj) {
            Functions::ajaxJsonString(array('html' => Yii::t('wiki', 'Sorry, We could not find those revisions.'), 'title' => ''));
        }

        // Print
        Functions::ajaxJsonString(array('html' => Functions::diffTexts(CHtml::encode($fromObj->content), CHtml::encode($toObj->content)), 'title' => Yii::t('wiki', 'Difference Report between revision #{a} and #{b}', array('{a}' => $revisionFrom, '{b}' => $revisionTo))));
    }

    /**
     * Delete a wiki page and all it's revisions
     */
    public function actionDelete() {
        if (Yii::app()->request->getParam('id') && ( $model = WikiPages::model()->findByPk(Yii::app()->request->getParam('id')) )) {
            Functions::setFlash(Yii::t('wiki', 'Wiki: Page Removed.'));
            $model->delete();
            $this->redirect(Yii::app()->request->getUrlReferrer());
        } else {
            $this->redirect(Yii::app()->request->getUrlReferrer());
        }
    }

    /**
     * Make a page as a start page
     */
    public function actionStartPage() {
        if (Yii::app()->request->getParam('id') && ( $model = WikiPages::model()->findByPk(Yii::app()->request->getParam('id')) )) {
            // Is it already a start page?
            if ($model->isstartpage) {
                Functions::setFlash(Yii::t('wiki', 'Wiki: Sorry, That page is already marked as the start page.'));
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }

            // Mark everyone as not then mark this as a start page
            $model->makeStartPage();
            // Flash and redirect
            Functions::setFlash(Yii::t('wiki', 'Wiki: Page marked as a start page.'));
            $this->redirect(Yii::app()->request->getUrlReferrer());
        } else {
            $this->redirect(Yii::app()->request->getUrlReferrer());
        }
    }

    /**
     * Delete a wiki page revision
     */
    public function actionDeleteRevision() {
        if (Yii::app()->request->getParam('id')
                && ( $revisionModel = WikiPagesRev::model()->with(array('page'))->findByPk(Yii::app()->request->getParam('id')) )) {
            // Is it the only revision?
            if (WikiPagesRev::model()->count('pageid=:pageid', array(':pageid' => $revisionModel->pageid)) <= 1) {
                Functions::setFlash(Yii::t('wiki', 'Wiki: Sorry, That is the only revision left. You can not delete the last one.'));
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }

            // Make sure the current revision is not the one we are trying to delete
            $pageModel = WikiPages::model()->findByPk($revisionModel->pageid);
            if ($pageModel->workingrevision == $revisionModel->revisionid) {
                Functions::setFlash(Yii::t('wiki', 'Wiki: Sorry, That revision is currently the active one. Please set the active revision to a different revision before deleting this one.'));
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }

            Functions::setFlash(Yii::t('wiki', 'Wiki: Page Revision Removed.'));
            $revisionModel->delete();
            $this->redirect(Yii::app()->request->getUrlReferrer());
        } else {
            $this->redirect(Yii::app()->request->getUrlReferrer());
        }
    }

    /**
     * Restore a wiki page revision
     */
    public function actionRestoreRevision() {
        if (Yii::app()->request->getParam('id')
                && ( $revisionModel = WikiPagesRev::model()->with(array('page'))->findByPk(Yii::app()->request->getParam('id')) )) {
            // Is it the only revision?
            if (WikiPagesRev::model()->count('pageid=:pageid', array(':pageid' => $revisionModel->pageid)) <= 1) {
                Functions::setFlash(Yii::t('wiki', 'Wiki: Sorry, That is the only revision left. You can not restore this revision as it is the only one.'));
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }

            // Make sure the current revision is not the one we are trying to restore
            $pageModel = WikiPages::model()->findByPk($revisionModel->pageid);
            if ($pageModel->workingrevision == $revisionModel->revisionid) {
                Functions::setFlash(Yii::t('wiki', 'Wiki: Sorry, That revision is currently the active one.'));
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }

            // Create a new revision that will be the active one
            $newRevisionModel = new WikiPagesRev;
            $newRevisionModel->pageid = $pageModel->id;
            $newRevisionModel->comment = Yii::t('wiki', 'Restored from revision #{n}', array('{n}' => $revisionModel->revisionid));
            $newRevisionModel->content = $revisionModel->content;
            if (!$newRevisionModel->save()) {
                Functions::setFlash(Yii::t('wiki', 'Wiki: There was a problem trying to restore that revision.'));
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }

            // Store log
            $newRevisionModel->restoreRevisionLog($revisionModel->revisionid);

            // Update working revision id for the page
            $pageModel->workingrevision = $newRevisionModel->revisionid;
            Activity::$toSave = false;
            $pageModel->update();

            Functions::setFlash(Yii::t('wiki', 'Wiki: Page Revision Restored.'));
            $this->redirect(Yii::app()->request->getUrlReferrer());
        } else {
            $this->redirect(Yii::app()->request->getUrlReferrer());
        }
    }

    /**
     * View a wiki page
     *
     */
    public function actionViewPage() {
        if (Yii::app()->request->getParam('id')
                && ( $model = WikiPages::model()->findByPk(Yii::app()->request->getParam('id')) )
                && ($revisionModel = WikiPagesRev::model()->find('pageid=:pageid AND revisionid=:revisionid', array(':pageid' => $model->id, ':revisionid' => $model->workingrevision)))) {
            $title = CHtml::encode($model->title);
            $this->pageTitle[] = $title;
            $this->render('show', array('title' => $title, 'model' => $model, 'revisionModel' => $revisionModel));
        } else {
            Functions::setFlash(Yii::t('wiki', 'Sorry, we could not find that page.'));
            $this->redirect(array('/wiki'));
        }
    }

    /**
     * View a wiki page revision
     *
     */
    public function actionViewPageRevision() {
        if (Yii::app()->request->getParam('id') && Yii::app()->request->getParam('revisionid')
                && ( $model = WikiPages::model()->findByPk(Yii::app()->request->getParam('id')) )
                && ($revisionModel = WikiPagesRev::model()->find('pageid=:pageid AND revisionid=:revisionid', array(':pageid' => $model->id, ':revisionid' => Yii::app()->request->getParam('revisionid'))))) {
            $title = CHtml::encode($model->title) . ' - ' . Yii::t('wiki', 'Revision #{n}', array('{n}' => $revisionModel->revisionid));
            $this->pageTitle[] = $title;
            $this->render('show', array('title' => $title, 'model' => $model, 'revisionModel' => $revisionModel));
        } else {
            Functions::setFlash(Yii::t('wiki', 'Sorry, we could not find that page with that revision.'));
            $this->redirect(array('/wiki'));
        }
    }

    /**
     * View a wiki page revisions list
     *
     */
    public function actionrevisions() {
        if (Yii::app()->request->getParam('id') && ( $model = WikiPages::model()->findByPk(Yii::app()->request->getParam('id')) )) {
            $revisions = WikiPagesRev::model()->with(array('author'))->byPageId($model->id)->orderBy('revisionid', 'desc')->findAll();
            $title = Yii::t('wiki', 'Viewing Wiki Page Revisions');
            $this->pageTitle[] = $title;
            $this->render('revisions', array('title' => $title, 'model' => $model, 'revisions' => $revisions));
        } else {
            Functions::setFlash(Yii::t('wiki', 'Sorry, we could not find that page.'));
            $this->redirect(array('/wiki'));
        }
    }

}