<?php

/**
 * Wiki Pages model
 */
class WikiPages extends ActiveRecordAbstract {

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
        return '{{wiki_pages}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array();
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('wiki', 'Wiki Page Name'),
            'description' => Yii::t('wiki', 'Wiki Page Description'),
            'alias' => Yii::t('wiki', 'Wiki Page Alias'),
            'status' => Yii::t('wiki', 'Wiki Page Status'),
            'projectid' => Yii::t('wiki', 'Wiki Page Project'),
            'comment' => Yii::t('wiki', 'Editing Comment'),
        );
    }

    /**
     * Before save operations
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->userid = Yii::app()->user->id;
            $this->status = 1;

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
        $this->activity['type'] = Activity::TYPE_WIKI;
        if ($this->isNewRecord) {
            $this->activity['title'] = 'Created a new wiki page <strong>{pagetitle}</strong>';
        } else {
            $this->activity['title'] = 'Updated the wiki page <strong>{pagetitle}</strong>';
            if ($this->workingrevision) {
                $this->activity['title'] .= ' Revision ' . $this->getRevisionLink();
            }
        }

        // Append project params
        $this->activity['params'] = array_merge($this->activity['params'], array('{pagetitle}' => $this->getLink()));

        // Activity: New Project
        Activity::log($this->activity);

        return parent::afterSave();
    }

    /**
     * Before delete event
     */
    public function beforeDelete() {
        // Delete all the wiki revisions for this page
        WikiPagesRev::model()->deleteAll('pageid=:pageid', array(':pageid' => $this->id));

        return parent::beforeDelete();
    }

    /**
     * After delete event
     */
    public function afterDelete() {
        $this->activity['projectid'] = $this->projectid;
        $this->activity['type'] = Activity::TYPE_WIKI;
        $this->activity['title'] = "Deleted The Wiki Page: <strong>{pagetitle}</strong> and all it's revisions.";

        // Append project params
        $this->activity['params'] = array_merge($this->activity['params'], array('{pagetitle}' => $this->title));

        // Activity: New Project
        Activity::log($this->activity);
        return parent::afterDelete();
    }

    /**
     * Update start page
     * @param int $pageId
     */
    public function makeStartPage() {
        Yii::app()->db->createCommand('UPDATE {{wiki_pages}} SET isstartpage=0')->execute();
        $this->isstartpage = 1;

        $this->activity['projectid'] = $this->projectid;
        $this->activity['type'] = Activity::TYPE_WIKI;
        $this->activity['title'] = "Marked <strong>{pagetitle}</strong> as a start page.";

        // Append project params
        $this->activity['params'] = array_merge($this->activity['params'], array('{pagetitle}' => $this->getLink()));

        // Activity: New Project
        Activity::log($this->activity);

        Activity::$toSave = false;
        $this->update();
    }

    /**
     * Global Scopes
     */
    public function scopes() {
        return array(
            'getActive' => array(
                'condition' => 't.status=1',
            ),
            'getArchived' => array(
                'condition' => 't.status=0',
            ),
            'byUser' => array(
                'condition' => 't.userid=:userid',
                'params' => array(':userid' => Yii::app()->user->id),
            ),
            'byDate' => array(
                'order' => 't.created DESC',
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
            array('title, projectid', 'required'),
            array('title', 'length', 'min' => 3, 'max' => 55),
            array('description', 'length', 'max' => 125),
            array('projectid', 'in', 'range' => array_keys(Projects::model()->getUserProjects(true))),
            array('status', 'safe'),
        );
    }

    /**
     * order the results by a certain value
     * @param string $order
     * @param string $sort
     * @return AR Object
     */
    public function orderBy($order = 'created', $sort = 'desc') {
        $this->getDbCriteria()->mergeWith(array(
            'order' => $order . ' ' . $sort,
        ));
        return $this;
    }

    /**
     * Get link
     */
    public function getLink() {
        return CHtml::link(CHtml::encode($this->title), array('/wiki/' . $this->id . '/' . $this->alias));
    }

    /**
     * Get link
     */
    public function getRevisionLink() {
        return CHtml::link('#' . $this->workingrevision, array('/wiki/' . $this->id . '/' . $this->alias . '/' . $this->workingrevision));
    }

    /**
     * Get link
     */
    public function getModelRevisionLink($revision, $id, $alias, $htmlOptions = array()) {
        return CHtml::link('#' . $revision, array('/wiki/' . $id . '/' . $alias . '/' . $revision), $htmlOptions);
    }

}