<?php
/**
 * Tickets Properties moderation
 */
class TicketProps extends BaseFormModel
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
			array('assignedtoid', 'safe' ),
			array('keywords', 'safe'),
			array('title', 'required'),
			array('title', 'length', 'min' => 3, 'max' => 100 ),
		);
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
            'keywords' => Yii::t('tickets', 'Ticket Keywords'),
            'title' => Yii::t('tickets', 'Ticket Title'),
		);
	}
	
}