<?php
/**
 * Site base controller class
 */
class SiteBaseController extends BaseController {
    /**
     * Javascript URL
     *
     * @var string
     */
    public $jsUrl = '';
    
    /**
     * Include language strings here to add to the js tracker object
     * @var array
     */
    public $jsLanguages = array();

    /**
     * Class constructor
     *
     */
    public function init() {

        // Add Js
        $this->jsUrl = Yii::app()->theme->baseUrl . '/scripts';

		// Add in someshortcuts
		Yii::app()->params['imagesUrl'] = Yii::app()->themeManager->baseUrl . '/images/';
		Yii::app()->params['styleUrl'] = Yii::app()->themeManager->baseUrl . '/styles/';
		Yii::app()->params['scriptUrl'] = Yii::app()->themeManager->baseUrl . '/scripts/';

		// Add default page title which is the application name
        $this->pageTitle[] = Yii::t('global', Yii::app()->name);
		
		// By default we register the robots to 'all'
		// we wil override this when we need to
        Yii::app()->clientScript->registerMetaTag( 'all', 'robots' );

		// We add a meta 'language' tag based on the currently viewed language
		Yii::app()->clientScript->registerMetaTag( Yii::app()->language, 'language', 'content-language' );

        /* Run init */
        parent::init();
    }
}