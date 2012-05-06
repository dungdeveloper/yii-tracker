<?php

class markitup extends CInputWidget
{
    /**
     * @var string - editor type
     * bbcode, wiki, markdown, html
     */
    public $editorType = 'html';
    /**
     * Init widget
     */
    public function run(){
        parent::run();

        list($name, $id) = $this->resolveNameID();

        $baseDir = dirname(__FILE__);
        $assets = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'markitup'.DIRECTORY_SEPARATOR.'markitup');

        $cs = Yii::app()->getClientScript();
        $widgetPath = Yii::getPathOfAlias('widgets.markitup.markitup.markitup.sets.' . $this->editorType);

        $cs->registerScriptFile($assets.'/jquery.markitup.js', CClientScript::POS_END );
		$cs->registerScriptFile($assets.'/sets/'.$this->editorType.'/set.js', CClientScript::POS_END);
		
		$cs->registerCssFile($assets.'/skins/simple/style.css');
		$cs->registerCssFile($assets.'/sets/'.$this->editorType.'/style.css');
		
		if($this->hasModel()) 
		{
            $html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        }
		else 
		{
            $html = CHtml::textArea($name, $this->value, $this->htmlOptions);
        }

        $js = "$('#{$id}').markItUp(mySettings);";
        $cs->registerScript('Yii.'.get_class($this).'#'.$id, $js, CClientScript::POS_READY);

        echo $html;

    }
}