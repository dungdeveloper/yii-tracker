<?php

class BaseFormModel extends CFormModel
{
    /**
     * @var array - override logged data
     */
    public $activity = array('description' => '', 'params' => array());
}