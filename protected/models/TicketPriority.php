<?php

/**
 * Tickets Priorities model
 */
class TicketPriority extends ActiveRecordAbstract {

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
        return '{{tickets_priorities}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'tickets' => array(self::HAS_MANY, 'Tickets', 'priority'),
            'ticketsCount' => array(self::STAT, 'Tickets', 'priority'),
        );
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('tickets', 'Priority Title'),
            'color' => Yii::t('tickets', 'Priority Color'),
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
            array('color', 'safe'),
        );
    }

    /**
     * Get priority link
     */
    public function getLink($title = null, $htmlOptions = array()) {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/priority/' . $this->id . '/' . $this->alias), $htmlOptions);
    }

    /**
     * Get priority rss link
     */
    public function getRssLink($title = null, $htmlOptions = array(), $type = 'rss') {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/priority/' . $this->id . '/' . $this->alias . '/' . $type), $htmlOptions);
    }

}