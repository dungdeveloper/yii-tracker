<?php

class search extends CWidget
{
    /**
     * @var string - the input id
     */
    public $id = 'search';
    /**
     * @var string - the suggestions box id
     */
    public $resultId = 'suggestions';
    /**
     * @var string - ajax url
     */
    public $ajaxLink = '';
    /**
     * Init widget
     */
    public function run() {
        // Publish
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        // Register resoruces
        $cs = Yii::app()->getClientScript();
		$cs->registerCssFile($assets.'/style.css');
        // Add to the page
        if($this->id) {
            // sort the ajax link
            if(!$this->ajaxLink) {
                $this->ajaxLink = Yii::app()->urlManager->createUrl('/search/suggestion');
            }
        
            $js = "
                    
                    // Fade out the suggestions box when not active
                     $('input').blur(function(){
                     	$('#".$this->resultId."').fadeOut('fast');
                     });
                     
                     $('#".$this->id."').keyup(function(){
                        if(!$(this).val()) {
                    		$('#".$this->resultId."').fadeOut(); // Hide the suggestions box
                    	} else {
                    		$.get('".$this->ajaxLink."', {term: $(this).val()}, function(data) { // Do an AJAX call
                    			$('#".$this->resultId."').fadeIn('fast'); // Show the suggestions box
                    			$('#".$this->resultId."').html(data); // Fill the suggestions box
                    		});
                    	}
                     });
                    ";
            $cs->registerScript('Yii.'.get_class($this).'#'.$this->id, $js, CClientScript::POS_READY);
        }
    }
}