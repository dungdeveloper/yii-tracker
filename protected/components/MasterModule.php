<?php

/**
 * Master Module class that represents the parent module
 * for all sub modules, each modules (admin, site, etc...)
 * Should extend from this class as it provides several common
 * operations, tasks and class members
 */
class MasterModule extends CWebModule {

    /**
     * Module constructor - Builds the initial module data
     *
     *
     * @author vadim
     *
     */
    public function init() {

        // If the langauge is set then set the application
        // Language appropriatly
        if (( isset($_GET['lang']) && in_array($_GET['lang'], array_keys(Yii::app()->params['languages'])))) {
            Yii::app()->setLanguage($_GET['lang']);
        }

        parent::init();
    }

}