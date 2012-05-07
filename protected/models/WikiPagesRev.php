<?php

/**
 * Wiki Pages Revision model
 */
class WikiPagesRev extends ActiveRecordAbstract {

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
        return '{{wiki_page_revision}}';
    }

    /**
     * Relations
     */
    public function relations() {
        return array(
            'author' => array(self::BELONGS_TO, 'Users', 'userid'),
            'page' => array(self::BELONGS_TO, 'WikiPages', 'pageid'),
        );
    }

    /**
     * After delete event
     */
    public function afterDelete() {
        $this->activity['projectid'] = $this->page->projectid;
        $this->activity['type'] = Activity::TYPE_WIKI;
        $this->activity['title'] = "Deleted Revision <strong>#{revid}</strong> for the page <strong>{pagetitle}</strong> ";

        // Append project params
        $this->activity['params'] = array_merge($this->activity['params'], array('{revid}' => $this->revisionid, '{pagetitle}' => $this->page->getLink()));

        // Activity: New Project
        Activity::log($this->activity);
        return parent::afterDelete();
    }

    /**
     * Restore revision log
     *
     */
    public function restoreRevisionLog($revisionId) {
        $this->activity['projectid'] = $this->page->projectid;
        $this->activity['type'] = Activity::TYPE_WIKI;
        $this->activity['title'] = "Restored Revision <strong>{revid}</strong> for the page <strong>{pagetitle}</strong> ";

        // Append project params
        $this->activity['params'] = array_merge($this->activity['params'], array('{revid}' => WikiPages::model()->getModelRevisionLink($revisionId, $this->page->id, $this->page->alias), '{pagetitle}' => $this->page->getLink()));

        // Activity: New Project
        Activity::log($this->activity);
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels() {
        return array(
            'content' => Yii::t('wiki', 'Content'),
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
        }

        // Increase the revision id by 1
        $this->revisionid = $this->maxPageRevision();

        return parent::beforeSave();
    }

    /**
     * @return int - the page id highest revision
     */
    public function maxPageRevision() {
        $max = Yii::app()->db->createCommand("SELECT MAX(revisionid) FROM {{wiki_page_revision}} WHERE pageid = '" . $this->pageid . "'")->queryScalar();
        return $max + 1;
    }

    /**
     * Global Scopes
     */
    public function scopes() {
        return array(
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
            array('content', 'length', 'min' => 3),
            array('comment', 'length', 'max' => 100),
        );
    }

    /**
     * Get revisions by page id
     * @param int $pageid
     * @return AR Object
     */
    public function byPageId($pageId) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.pageid=:pageid',
            'params' => array(':pageid' => $pageId),
        ));
        return $this;
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

}