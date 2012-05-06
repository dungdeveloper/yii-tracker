<?php

/**
 * Projects controller
 */
class ProjectsController extends SiteBaseController {

    /**
     * Controller constructor
     */
    public function init() {
        // Add in the required js
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/public/scripts/projects.js', CClientScript::POS_END);

        // Add title
        $this->pageTitle[] = Yii::t('projects', 'Projects');

        parent::init();
    }

    /**
     * Index action
     */
    public function actionIndex() {
        $projects = Projects::model()->getActive()->byUser()->byDate()->findAll();
        $this->render('index', array('projects' => $projects));
    }

    /**
     * Archived action
     */
    public function actionArchived() {
        $projects = Projects::model()->getArchived()->byUser()->byDate()->findAll();
        $this->render('archived', array('projects' => $projects));
    }

    /**
     * New Project action
     */
    public function actionNew() {
        $model = new Projects;
        if (isset($_POST['Projects'])) {
            $model->setAttributes($_POST['Projects']);
            if ($model->save()) {
                Functions::setFlash(Yii::t('projects', 'Project Created.'));
                $this->redirect(array('/projects'));
            }
        }
        // Add title
        $this->pageTitle[] = Yii::t('projects', 'Creating New Project');
        $this->render('new', array('model' => $model));
    }

    /**
     * Edit Project action
     */
    public function actionEdit() {
        if (Yii::app()->request->getParam('id') && ( $model = Projects::model()->byUser()->findByPk(Yii::app()->request->getParam('id')) )) {
            if (isset($_POST['Projects'])) {
                // Add description to the activity
                if ($_POST['Projects']['title'] != $model->title) {
                    $model->activity['description'] = 'Changed title from <strong>{old}</strong> to <strong>{new}</strong>';
                    $model->activity['params']['{old}'] = $model->title;
                    $model->activity['params']['{new}'] = $_POST['Projects']['title'];
                }
                $model->setAttributes($_POST['Projects']);
                if ($model->save()) {
                    Functions::setFlash(Yii::t('projects', 'Project Updated.'));
                    $this->redirect(array('/projects'));
                }
            }
            // Add title
            $this->pageTitle[] = Yii::t('projects', 'Editing Project');
            $this->render('edit', array('model' => $model));
        } else {
            $this->redirect(array('/projects'));
        }
    }

    /**
     * Change Project status action
     */
    public function actionStatus() {
        if (Yii::app()->request->getParam('id') && ( $model = Projects::model()->byUser()->findByPk(Yii::app()->request->getParam('id')) )) {
            // Check it's current status and update
            $currentStatus = $model->status;
            $model->status = $currentStatus == 0 ? 1 : 0;
            // Add description to the activity
            $model->activity['description'] = $model->status ? 'Project Activated.' : 'Project Archived.';
            $model->update();

            Functions::ajaxString($model->status ? Yii::t('projects', 'Project Activated.') : Yii::t('projects', 'Project Archived.') );
        } else {
            Functions::ajaxError(Yii::t('error', 'We could not find that project.'));
        }
    }

    /**
     * View project
     */
    public function actionViewProject() {
        var_dump($_GET);
    }

}