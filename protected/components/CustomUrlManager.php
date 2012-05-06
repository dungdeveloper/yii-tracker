<?php

/**
 * Custom rules manager class
 *
 * Override to load the routes from the DB rather then a file
 *
 */
class CustomUrlManager extends CUrlManager {

    /**
     * Build the rules from the DB
     */
    protected function processRules() {
        if (($this->rules = Yii::app()->cache->get('customurlrules')) === false) {
            // Site Rules
            $this->rules = array(
                // Projects
                'project/<id:\d+>/<alias>' => 'site/projects/viewproject',
                // Tickets
                'issue/<id:\d+>/<alias>' => 'site/tickets/viewticket',
                'issues/<type:(priority|version|fixedin|type|status|category|project|milestone)>/<id:\d+>/<alias>' => 'site/tickets/filterissues',
                'issues/<type:(priority|version|fixedin|type|status|category|project|milestone)>/<id:\d+>/<alias>/<rss:\w+>' => 'site/tickets/filterissues',
                'issues/tag/<tag>' => 'site/tickets/filterissuesbytag',
                // Wiki
                'wiki/<id:\d+>/<alias>' => 'site/wiki/viewpage',
                'wiki/<id:\d+>/<alias>/<revisionid:\d+>' => 'site/wiki/viewpagerevision',
                // General
                '' => 'site/index/index',
                '<_c:([a-zA-z0-9-]+)>' => 'site/<_c>/index',
                '<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>' => 'site/<_c>/<_a>',
                '<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>//*' => 'site/<_c>/<_a>/',
            );

            Yii::app()->cache->set('customurlrules', $this->rules);
        }

        // Run parent
        parent::processRules();
    }

    /**
     * Clear the url manager cache
     */
    public function clearCache() {
        Yii::app()->cache->delete('customurlrules');
    }

    /**
     * Creates an absolute URL based on the given controller and action information.
     * @param string the URL route. This should be in the format of 'ControllerID/ActionID'.
     * @param array additional GET parameters (name=>value). Both the name and value will be URL-encoded.
     * @param string schema to use (e.g. http, https). If empty, the schema used for the current request will be used.
     * @param string the token separating name-value pairs in the URL.
     * @return string the constructed URL
     */
    public function createAbsoluteUrl($route, $params=array(), $schema='', $ampersand='&') {
        return Yii::app()->getRequest()->getHostInfo($schema) . $this->createUrl($route, $params, $ampersand);
    }

}
