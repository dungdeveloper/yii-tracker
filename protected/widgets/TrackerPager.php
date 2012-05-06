<?php
/**
 * Tracker Pager Widget
 * Mainly used to set some custom styles
 */
class TrackerPager extends CLinkPager
{   
    /**
     * @var string - defaults header is empty
     */
    public $header = '';
    /**
     * @var array - html options used in the pager ul
     */
    public $htmlOptions = array('class' => 'trackerPager');
    /**
     * Override the run method to set the custom cssFile
     * property
     * @return object
     */
    public function run() {
        // Assign the new pager css
        $this->cssFile = Yii::app()->themeManager->baseUrl . '/pager.css';
        parent::run();
    }
}