<?php

class fancybox extends CWidget
{
    /**
     * Array of settings for the fancybox that will be
     * json encoded
     * @var array
     */
    public $options = array();
    /**
     * @var string - the input id
     */
    public $id = '';
    /**
     * Init widget
     */
    public function run() {
        // Publish
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        // Register resoruces
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($assets.'/jquery.fancybox-1.3.4.pack.js', CClientScript::POS_END );
		$cs->registerCssFile($assets.'/jquery.fancybox-1.3.4.css');
        // Add to the page
        if($this->id) {
            $js = "$('#".$this->id."').fancybox(".CJSON::encode($this->options).");";
            $cs->registerScript('Yii.'.get_class($this).'#'.$this->id, $js, CClientScript::POS_READY);
        }
    }
}