<?php

/**
 * Tickets Versions model
 */
class TicketVersion extends ActiveRecordAbstract {

    /**
     * @return object
     */
    public static function model() {
        return parent::model(__CLASS__);
    }

    /**
     * @return string Table name
     */
    public function tableName() {
        return '{{tickets_versions}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'tickets' => array(self::HAS_MANY, 'Tickets', 'ticketversion'),
            'ticketsFixedin' => array(self::HAS_MANY, 'Tickets', 'fixedin'),
            'ticketsFixedinCount' => array(self::STAT, 'Tickets', 'fixedin'),
            'ticketsCount' => array(self::STAT, 'Tickets', 'ticketversion'),
        );
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('tickets', 'Version Title'),
        );
    }

    /**
     * Before save operations
     */
    public function beforeSave() {
        // Create the alias out of the title
        $this->alias = Yii::app()->func->makeAlias($this->title);

        return parent::beforeSave();
    }

    /**
     * afterSave event
     *
     */
    public function afterSave() {
        // @todo add activity log
        return parent::afterSave();
    }

    /**
     * table data rules
     *
     * @return array
     */
    public function rules() {
        return array(
            array('title', 'required'),
            array('title', 'length', 'min' => 3, 'max' => 55),
        );
    }

    /**
     * Get version link
     */
    public function getVersionLink($title = null, $htmlOptions = array()) {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/version/' . $this->id . '/' . $this->alias), $htmlOptions);
    }

    /**
     * Get version link
     */
    public function getFixedinLink($title = null, $htmlOptions = array()) {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/fixedin/' . $this->id . '/' . $this->alias), $htmlOptions);
    }

    /**
     * Get version rss link
     */
    public function getRssLink($title = null, $htmlOptions = array(), $type = 'rss') {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/version/' . $this->id . '/' . $this->alias . '/' . $type), $htmlOptions);
    }

    /**
     * Get fixed in rss link
     */
    public function getFixedRssLink($title = null, $htmlOptions = array(), $type = 'rss') {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/fixedin/' . $this->id . '/' . $this->alias . '/' . $type), $htmlOptions);
    }

}