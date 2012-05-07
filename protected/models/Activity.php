<?php

/**
 * Activity model
 */
class Activity extends CActiveRecord {
    /**
     * Activity types
     */

    const TYPE_PROJECT = 1;
    const TYPE_WIKI = 2;
    const TYPE_TICKET = 3;

    /**
     * @var boolean - mark this to false if you don't wanna save a log
     */
    public static $toSave = true;

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
        return '{{activity}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'author' => array(self::BELONGS_TO, 'Users', 'userid'),
        );
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('activity', 'Activity Name'),
            'description' => Yii::t('activity', 'Activity Description'),
        );
    }

    /**
     * Log activity to the activity table
     * pass an array of the following elements
     * title -> required yiit message
     * description -> short description
     * type -> type of activity one of the constants above
     * description -> short description up to 125chars
     * params -> serialized array of data to the yiit third argument array of title and description
     * projectid -> the project working on
     * @param $data
     * @return obj
     */
    public static function log(array $data) {
        if (!self::$toSave) {
            return false;
        }
        $model = new self;
        $model->title = $data['title'];
        $model->description = isset($data['description']) ? $data['description'] : '';
        $model->type = $data['type'];
        $model->params = isset($data['params']) ? serialize($data['params']) : '';
        $model->projectid = $data['projectid'];
        return $model->save();
    }

    /**
     * Before save operations
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->userid = Yii::app()->user->id;
        }

        return parent::beforeSave();
    }

    /**
     * Global Scopes
     */
    public function scopes() {
        return array(
            'byType' => array(
                'order' => 'type ASC',
            ),
            'byDate' => array(
                'order' => 'created DESC',
            ),
            'byUser' => array(
                'condition' => 'userid=:userid',
                'params' => array(':userid' => Yii::app()->user->id),
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
            array('title', 'required'),
        );
    }

    /**
     * Get activity based on the projects the user is active in
     * @todo add a filter to return records related to the users project
     * @return array
     */
    public function getMyActivity() {
        $criteria = new CDbCriteria;
        $criteria->limit = Yii::app()->params['activityLimit'];
        $activities = self::model()->byDate()->with(array('author'))->findAll($criteria);
        // Sort activities by day, Starting from today and down
        $rows = array();

        foreach ($activities as $activity) {
            $rows[date('m-d-Y', $activity->created)][] = $activity;
        }

        return $rows;
    }

    /**
     * Return the params as array
     */
    public function getParams() {
        $params = @unserialize($this->params);
        return (is_array($params) && count($params)) ? $params : array();
    }

}