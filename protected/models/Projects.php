<?php
/**
 * Projects model
 */
class Projects extends ActiveRecordAbstract
{	
	/**
	 * @return object
	 */
	public static function model()
	{
		return parent::model(__CLASS__);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return '{{projects}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array();
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'title' => Yii::t('projects', 'Project Name'),
			'description' => Yii::t('projects', 'Project Description'),
			'alias' => Yii::t('projects', 'Project Alias'),
			'status' => Yii::t('projects', 'Project Status'),
		);
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->created = time();
			$this->userid = Yii::app()->user->id;
			$this->status = 1;
		}
		
        // Create the alias out of the title
        $this->alias = Yii::app()->func->makeAlias($this->title);
		
		return parent::beforeSave();
	}
	
	/**
	 * afterSave event
	 *
	 */
	public function afterSave() {
	    $this->activity['projectid'] = $this->id;
	    $this->activity['type'] = Activity::TYPE_PROJECT;
        if( $this->isNewRecord ) {
            $this->activity['title'] = 'Created a new project <strong>{projecttitle}</strong>';
		} else {
            $this->activity['title'] = 'Updated the project <strong>{projecttitle}</strong>';
		}
		
		// Append project params
		$this->activity['params'] = array_merge($this->activity['params'], array('{projecttitle}' => $this->getLink()));

        // Activity: New Project
        Activity::log($this->activity);
		
	   return parent::afterSave();
	}
	
	/**
	 * Global Scopes
	 */
	public function scopes() {
        return array(
            'getActive'=>array(
                  'condition'=>'status=1',
            ),
            'getArchived'=>array(
                  'condition'=>'status=0',
            ),
            'byUser'=>array(
                  'condition'=>'userid=:userid',
                  'params' => array(':userid' => Yii::app()->user->id),
            ),
            'byDate'=>array(
                  'order'=>'created DESC',
            ),
        );
	}
	
	/**
	 * Get an array of users projects, if we pass a boolean value
	 * true as the first argument only the active projects will 
	 * be returned.
	 * 
	 * @param boolean $onlyActive
	 * @return array
	 */
	public function getUserProjects($onlyActive=false) {
	   $array = array();
	   foreach(self::model()->byUser()->findAll() as $row) {
	       if($onlyActive == true && !$row->status) {
	           continue;
	       }
	       $array[ $row->id ] = $row->title;
	   }
	   return $array;
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title', 'required' ),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('description', 'length', 'max' => 125 ),
			array('status', 'safe'),
		);
	}
	
	/**
	 * Get member profile link
	 */
	public function getLink()
	{
		return CHtml::link( CHtml::encode($this->title), array('/project/' . $this->id . '/' . $this->alias ) );
	}
}