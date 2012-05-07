<?php

/**
 * Tickets Comments model
 */
class TicketComments extends ActiveRecordAbstract {

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
        return '{{tickets_comments}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'commentreporter' => array(self::BELONGS_TO, 'Users', 'userid'),
            'ticket' => array(self::BELONGS_TO, 'Users', 'ticketid'),
            'changes' => array(self::HAS_MANY, 'TicketChanges', 'commentid'),
        );
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'content' => Yii::t('tickets', 'Comment Content'),
        );
    }

    /**
     * Before save operations
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->posted = time();
            $this->userid = Yii::app()->user->id;
        }

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
     * Global Scopes
     */
    public function scopes() {
        return array(
            'byDate' => array(
                'order' => 'posted DESC',
            ),
        );
    }

    /**
     * table data rules
     *
     * @return array
     */
    public function rules() {
        return array(
            array('content', 'safe'),
            array('ticketid', 'numerical', 'min' => 1),
        );
    }

    /**
     * Get member profile link
     */
    public function getLink() {
        return CHtml::link(CHtml::encode($this->title), array('/issue/' . $this->ticket->id . '/' . $this->ticket->alias, '#comment-' => $this->id));
    }

}