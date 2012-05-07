<?php

/**
 * Tickets model
 */
class Tickets extends ActiveRecordAbstract {

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
        return '{{tickets}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'reporter' => array(self::BELONGS_TO, 'Users', 'reportedbyid'),
            'assigned' => array(self::BELONGS_TO, 'Users', 'assignedtoid'),
            'project' => array(self::BELONGS_TO, 'Projects', 'projectid'),
            'lastcomment' => array(self::BELONGS_TO, 'TicketComments', 'lastcommentid'),
            'comments' => array(self::HAS_MANY, 'TicketComments', 'ticketid'),
            'commentsCount' => array(self::STAT, 'TicketComments', 'ticketid', 'condition' => 't.firstcomment!=1'),
            'status' => array(self::BELONGS_TO, 'TicketStatus', 'ticketstatus'),
            'type' => array(self::BELONGS_TO, 'TicketType', 'tickettype'),
            'category' => array(self::BELONGS_TO, 'TicketCategory', 'ticketcategory'),
            'version' => array(self::BELONGS_TO, 'TicketVersion', 'ticketversion'),
            'fixed' => array(self::BELONGS_TO, 'TicketVersion', 'fixedin'),
            'ticketpriority' => array(self::BELONGS_TO, 'TicketPriority', 'priority'),
            'ticketmilestone' => array(self::BELONGS_TO, 'Milestones', 'milestone'),
        );
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('tickets', 'Ticket Title'),
            'content' => Yii::t('tickets', 'Ticket Content'),
            'projectid' => Yii::t('tickets', 'Ticket Project'),
            'ticketcategory' => Yii::t('tickets', 'Ticket Category'),
            'ticketstatus' => Yii::t('tickets', 'Ticket Status'),
            'tickettype' => Yii::t('tickets', 'Ticket Type'),
            'priority' => Yii::t('tickets', 'Ticket Priority'),
            'milestone' => Yii::t('tickets', 'Ticket Milestone'),
            'ticketversion' => Yii::t('tickets', 'Version'),
            'fixedin' => Yii::t('tickets', 'Fixed In'),
            'assignedtoid' => Yii::t('tickets', 'Assigned To'),
            'keywords' => Yii::t('tickets', 'Keywords'),
        );
    }

    /**
     * Before save operations
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->posted = time();
            $this->reportedbyid = Yii::app()->user->id;

            // Create the alias out of the title
            $this->alias = Yii::app()->func->makeAlias($this->title);
        }

        return parent::beforeSave();
    }

    /**
     * afterSave event
     *
     */
    public function afterSave() {
        $this->activity['projectid'] = $this->projectid;
        $this->activity['type'] = Activity::TYPE_TICKET;
        if ($this->isNewRecord) {
            $this->activity['title'] = 'Created a new ticket <strong>{tickettitle}</strong>';
        } else {
            $this->activity['title'] = 'Updated ticket <strong>{tickettitle}</strong>';
        }

        // Append project params
        $this->activity['params'] = array_merge($this->activity['params'], array('{tickettitle}' => $this->getLink()));

        // Activity: New Project
        Activity::log($this->activity);

        return parent::afterSave();
    }

    /**
     * Global Scopes
     */
    public function scopes() {
        return array(
            'byReporter' => array(
                'condition' => 't.reportedbyid=:reportedbyid',
                'params' => array(':reportedbyid' => Yii::app()->user->id),
            ),
            'byAssignedTo' => array(
                'condition' => 't.assignedtoid=:assignedtoid',
                'params' => array(':assignedtoid' => Yii::app()->user->id),
            ),
            'byDate' => array(
                'order' => 'posted DESC',
            ),
            'byCommentDate' => array(
                'order' => 'comments.posted ASC',
            ),
            'byLastCommentDate' => array(
                'order' => 'lastcomment.posted DESC, t.posted DESC',
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
            array('title, content', 'required'),
            array('title', 'length', 'min' => 3, 'max' => 100),
            array('keywords', 'length', 'max' => 100),
            array('content', 'length', 'min' => 3),
            array('projectid', 'in', 'range' => array_keys(Projects::model()->getUserProjects(true)), 'allowEmpty' => false, 'on' => 'ticketupdate'),
            array('ticketstatus', 'in', 'range' => CHtml::listData(TicketStatus::model()->findAll(), 'id', 'id')),
            array('tickettype', 'in', 'range' => CHtml::listData(TicketType::model()->findAll(), 'id', 'id')),
            array('ticketcategory', 'in', 'range' => CHtml::listData(TicketCategory::model()->findAll(), 'id', 'id')),
            array('priority', 'in', 'range' => CHtml::listData(TicketPriority::model()->findAll(), 'id', 'id')),
            array('ticketversion', 'in', 'range' => CHtml::listData(TicketVersion::model()->findAll(), 'id', 'id')),
            array('fixedin', 'in', 'range' => CHtml::listData(TicketVersion::model()->findAll(), 'id', 'id')),
            array('assignedtoid', 'checkAssignedTo'),
        );
    }

    /**
     * Check to make sure the username entered matches a record in the db
     *
     */
    public function checkAssignedTo() {
        // If we saved the assignedtoid then convert it to an id and not the username
        if ($this->assignedtoid) {
            // Find the username with this name
            if (is_int($this->assignedtoid)) {
                $user = Users::model()->findByPk($this->assignedtoid);
            } else {
                $user = Users::model()->find('username=:username', array(':username' => $this->assignedtoid));
            }
            if ($user) {
                $this->assignedtoid = $user->id;
            } else {
                $this->addError('assignedtoid', Yii::t('tickets', 'The user you are trying to assign the ticket was not found.'));
            }
        }
    }

    /**
     * Parse keywords into links
     *
     */
    public function getKeywords($htmlOptions = array()) {
        if (!$this->keywords) {
            return '--';
        }
        $keywords = array();
        foreach (explode(',', $this->keywords) as $keyword) {
            // Sanitize
            $keyword = CHtml::encode(trim($keyword));
            if (!$keyword) {
                continue; // empty spaces
            }
            $keywords[] = CHtml::link($keyword, array('/issues/tag/' . $keyword), $htmlOptions);
        }
        return implode(', ', $keywords);
    }

    /**
     * Get member profile link
     */
    public function getLink($title = null, $htmlOptions = array(), $returnLink = false) {
        $link = array('/issue/' . $this->id . '/' . $this->alias);
        if ($returnLink) {
            return $link;
        }
        return CHtml::link($title ? $title : CHtml::encode($this->title), $link, $htmlOptions);
    }

}