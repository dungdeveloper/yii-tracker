<?php

/**
 * Tickets Types model
 */
class TicketType extends ActiveRecordAbstract {

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
        return '{{tickets_types}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'tickets' => array(self::HAS_MANY, 'Tickets', 'tickettype'),
            'ticketsCount' => array(self::STAT, 'Tickets', 'tickettype'),
        );
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('tickets', 'Type Title'),
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
     * Get type link
     */
    public function getLink($title = null, $htmlOptions = array()) {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/type/' . $this->id . '/' . $this->alias), $htmlOptions);
    }

    /**
     * Get type rss link
     */
    public function getRssLink($title = null, $htmlOptions = array(), $type = 'rss') {
        return CHtml::link($title ? $title : CHtml::encode($this->title), array('/issues/type/' . $this->id . '/' . $this->alias . '/' . $type), $htmlOptions);
    }

}