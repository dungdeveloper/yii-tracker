<?php

class ActiveRecordAbstract extends CActiveRecord
{
    /**
     * @var array - override logged data
     */
    public $activity = array('description' => '', 'params' => array());
}