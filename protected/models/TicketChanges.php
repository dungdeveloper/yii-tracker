<?php

/**
 * Tickets Changes model
 */
class TicketChanges extends ActiveRecordAbstract {

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
        return '{{tickets_changes}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'reporter' => array(self::BELONGS_TO, 'Users', 'userid'),
            'ticket' => array(self::BELONGS_TO, 'Tickets', 'ticketid'),
            'commentchanges' => array(self::HAS_MANY, 'TicketComments', 'commentid'),
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
            array('content', 'required'),
            array('ticketid, commentid', 'required'),
        );
    }

}