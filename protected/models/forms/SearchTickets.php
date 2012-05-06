<?php
/**
 * Search Tickets Model
 */
class SearchTickets extends BaseFormModel
{
    /**
     * @var string
     */
	public $status;
	public $type;
	public $category;
	public $version;
	public $fixedin;
	public $priority;
	public $assignedtoid;
	public $keywords;
	public $title;
	public $reportedby;
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules() {
		return array(
			array('status, type, category, priority, version, fixedin', 'safe' ),
			array('assignedtoid', 'checkAssignedTo' ),
			array('reportedby', 'checkReportedBy' ),
			array('title, keywords', 'length', 'max' => 100 ),
		);
	}
	
	/**
	 * Check to make sure the username entered matches a record in the db
	 *
	 */
	public function checkReportedBy() {
	   // If we saved the assignedtoid then convert it to an id and not the username
		if($this->reportedby) {
            // Find the username with this name
            if(is_int($this->reportedby)) {
                $user = Users::model()->findByPk($this->reportedby);
            } else {
                $user = Users::model()->find('username=:username', array(':username' => $this->reportedby));
            }
            if($user) {
                $this->reportedby = $user->id;
            } else {
                $this->addError('reportedby', Yii::t('tickets', 'The username provided in the reporter by field is invalid.'));
            }
		}
	}
	
	/**
	 * Check to make sure the username entered matches a record in the db
	 *
	 */
	public function checkAssignedTo() {
	   // If we saved the assignedtoid then convert it to an id and not the username
		if($this->assignedtoid) {
            // Find the username with this name
            if(is_int($this->assignedtoid)) {
                $user = Users::model()->findByPk($this->assignedtoid);
            } else {
                $user = Users::model()->find('username=:username', array(':username' => $this->assignedtoid));
            }
            if($user) {
                $this->assignedtoid = $user->id;
            } else {
                $this->addError('assignedtoid', Yii::t('tickets', 'The username provided in the assigned to field is invalid.'));
            }
		}
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels() {
		return array(
			'status' => Yii::t('tickets', 'Ticket Status'),
			'priority' => Yii::t('tickets', 'Ticket Priority'),
			'type' => Yii::t('tickets', 'Ticket Type'),
			'category' => Yii::t('tickets', 'Ticket Category'),
			'version' => Yii::t('tickets', 'Ticket Version'),
            'fixedin' => Yii::t('tickets', 'Ticket Fixed In'),
            'assignedtoid' => Yii::t('tickets', 'Ticket Assigned To'),
            'reportedby' => Yii::t('tickets', 'Ticket Reported By'),
            'keywords' => Yii::t('tickets', 'Ticket Keywords'),
            'title' => Yii::t('tickets', 'Ticket Title'),
		);
	}
	
}