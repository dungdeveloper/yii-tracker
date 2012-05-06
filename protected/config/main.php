<?php

// Sort cache options
$caches = array();
$fastCache = true;

// Sort the type of cache to use
if (function_exists('xcache_isset')) {
    // Using XCache
    $caches = array('class' => 'CXCache');
} else if (extension_loaded('apc')) {
    // Using APC
    $caches = array('class' => 'CApcCache');
} else if (function_exists('eaccelerator_get')) {
    // Using Eaccelerator
    $caches = array('class' => 'CEAcceleratorCache');
} else if (function_exists('zend_shm_cache_store')) {
    // Using ZendDataCache
    $caches = array('class' => 'CZendDataCache');
} else {
    // Using File Cache - fallback
    $caches = array('class' => 'CFileCache', 'directoryLevel' => 2);
    $fastCache = false;
}

// Do we have apache mod_rewrite installed?
$showScriptName = true;
if (in_array('mod_rewrite', apache_get_modules())) {
    $showScriptName = false;
}

// Required system configuration. There should be no edit performed here.
return array(
    'preload' => array('log', 'session', 'db', 'cache'),
    'basePath' => ROOT_PATH . 'protected/',
    'modules' => array(
        'site' => array(
            'import' => array('site.components.*'),
            'layout' => 'main'
        ),
    ),
    'import' => array(
        'application.components.*',
        'application.models.*',
        'application.models.models.*',
        'application.models.forms.*',
    ),
    'theme' => 'default',
    'name' => 'My Tracker',
    'defaultController' => 'site/index',
    'layout' => 'main',
    'charset' => 'UTF-8',
    'sourceLanguage' => 'en',
    'language' => 'en',
    'params' => array(
        'fastcache' => $fastCache,
        'languages' => array('en' => 'English', 'he' => 'Hebrew'),
        'default_group' => 'user',
        'emailin' => 'dungdeveloper@gmail.com',
        'emailout' => 'dungdeveloper@gmail.com',
        // Settings
        'ticketsPerPage' => 100,
        'activityLimit' => 200,
    ),
    'aliases' => array(
        'helpers' => 'application.widgets',
        'widgets' => 'application.widgets',
    ),
    'components' => array(
        'format' => array(
            'class' => 'CFormatter',
        ),
        'email' => array(
            'class' => 'application.extensions.email.Email',
        ),
        'func' => array(
            'class' => 'application.components.Functions',
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error/error',
        ),
        /* 'settings' => array(
          'class' => 'XSettings',
          ),
          'authManager'=>array(
          'class'=>'AuthManager',
          'connectionID'=>'db',
          'itemTable' => 'authitem',
          'itemChildTable' => 'authitemchild',
          'assignmentTable' => 'authassignment',
          'defaultRoles'=>array('guest'),
          ), */
        'user' => array(
            'class' => 'CustomWebUser',
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
        ),
        'urlManager' => array(
            'class' => 'CustomUrlManager',
            'urlFormat' => $showScriptName ? 'get' : 'path',
            'cacheID' => 'cache',
            'showScriptName' => $showScriptName,
            'appendParams' => true,
        ),
        'request' => array(
            'class' => 'CHttpRequest',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'sessionTableName' => 'sessions',
            'connectionID' => 'db',
            'timeout' => 3600,
            'sessionName' => 'SECSESS',
        ),
        'cache' => $caches,
    ),
);
