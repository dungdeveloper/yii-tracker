<?php
/**
 * Tickets quick moderation
 */
class TicketsQuickModeration extends BaseFormModel
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
	
	/**
	 * @var array - array of the ids we are about to update
	 */
	public $ids = array();
	
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules() {
		return array(
			array('status', 'in', 'range' => CHtml::listData(TicketStatus::model()->findAll(),'id', 'id')  ),
			array('type', 'in', 'range' => CHtml::listData(TicketType::model()->findAll(),'id', 'id') ),
			array('category', 'in', 'range' => CHtml::listData(TicketCategory::model()->findAll(),'id', 'id') ),
			array('priority', 'in', 'range' => CHtml::listData(TicketPriority::model()->findAll(),'id', 'id') ),
			array('version', 'in', 'range' => CHtml::listData(TicketVersion::model()->findAll(),'id', 'id') ),
			array('fixedin', 'in', 'range' => CHtml::listData(TicketVersion::model()->findAll(),'id', 'id') ),
			array('assignedtoid', 'checkAssignedTo' ),
		);
	}
	
	/**
	 * Check to make sure the username entered matches a record in the db
	 *
	 */
	public function checkAssignedTo() {
	   // If we saved the assignedtoid then convert it to an id and not the username
		if($this->assignedtoid) {
            // Find the username with this name
            $user = Users::model()->find('username=:username', array(':username' => $this->assignedtoid));
            if($user) {
                $this->assignedtoid = $user->id;
            } else {
                // In this case we do not assign it
                $this->assignedtoid = '';
            }
		}
	}
	
	/**
	 * After validate event
	 * @return object
	 */
	public function afterValidate() {
	   if(count($this->ids)) {
	       $save = array();
	       // The values we would like to check if they were changed
	       // key = value from this model
	       // value = value from the tickets model
	       $valuesChanged = array(
	                       'status' => 'ticketstatus',
	                       'type' => 'tickettype',
	                       'category' => 'ticketcategory',
	                       'priority' => 'priority',
	                       'version' => 'ticketversion',
	                       'fixedin' => 'fixedin',
	                       'assignedtoid' => 'assignedtoid',
	       );
	       
	       // Loop the values
	       foreach($valuesChanged as $key => $value) {
	           if($this->$key) {
	               $save[$value] = $this->$key;
	           }
	       }
	       
	       // Only update if we have any
	       if(count($save)) {
	           // Update all the ticket ids
	           Tickets::model()->updateByPk($this->ids, $save);
	           
	           // Load one ticket model
    	        $model = Tickets::model()->findByPk(reset($this->ids));
    	       
                $this->activity['projectid'] = $model->projectid;
                $this->activity['type'] = Activity::TYPE_TICKET;
                $this->activity['title'] = 'Used multi-Moderation to update <strong>{n}</strong> {title}.';
                
                // Append project params
                $this->activity['params'] = array_merge($this->activity['params'], array('{n}' => count($this->ids), '{title}' => CHtml::link(Yii::t('tickets', 'Tickets'), array('/tickets'))));
                
                // Activity: New Project
                Activity::log($this->activity);
	       }
	   }
	   return parent::afterValidate();
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
		);
	}
	
}