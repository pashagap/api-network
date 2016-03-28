<?php

namespace Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;

/**
 * Class ActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM
 */
class ActiveRecord extends AbstractActiveRecord
{
    protected $lastModifiedBy = null;
    protected $lastModifiedDate = null;

    protected $primaryModel = 'inspections.template.model';

    public static $collection = 'InspectionsTemplate';

    public static $attrs
        = [
            'name' => ['type' => 'string'],
            'description' => ['type' => 'string'],
            'tags' => ['type' => 'array'],

            'location' => [
                'model' => 'Hatch\Core\Model\Entities\Location\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'createdBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'createdDate' => ['type' => 'date'],

            'modifiedBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'modifiedDate' => ['type' => 'date']
        ];

    protected function __preUpdate()
    {
        $this->refreshModifiedData();

        return parent::__preUpdate();
    }

    protected function refreshModifiedData()
    {
        if(null === $this->lastModifiedDate) {
            $this->updateToCurrentDate('modifiedDate');
        }
        if(null === $this->lastModifiedBy) {
            $this->updateToCurrentUser('modifiedBy');
        }
    }

    protected function __preInsert()
    {
        $this->updateToCurrentDate('createdDate');
        $this->updateToCurrentUser('createdBy');

        $this->modifiedDate = $this->createdDate;
        $this->modifiedBy = $this->createdBy;

        return parent::__preInsert();
    }

    public function getDescription()
    {
        return $this->__getter('description');
    }

    public function setDescription($description)
    {
        $this->__setter('description', $description);
    }

    public function getTags()
    {
        return $this->__getter('tags');
    }

    public function setTags($tags)
    {
        $this->__setter('tags', $tags);
    }

    private function updateToCurrentDate($fieldName)
    {
        $currentDatetime = new \DateTime();
        $currentMongoDate = new \MongoDate($currentDatetime->getTimestamp());
        $this->{$fieldName} = $currentMongoDate;
    }

    private function updateToCurrentUser($fieldName)
    {
        /** @var UserModel $userModel */
        $userModel = $this->sysApp['core.user.model'];
        $currentUser = $this->sysApp->getCurrentUser();

        $user = $userModel->readOne(
            [
                '_id' => new \MongoId($currentUser['id'])
            ]
        );

        $this->{$fieldName} = $user;
    }

    public function setModifiedDate($modifiedDate)
    {
        $this->lastModifiedDate = $this->getModifiedDate();

        $this->__setter('modifiedDate', $modifiedDate);
    }

    public function getModifiedDate()
    {
        return $this->__getter('modifiedDate');
    }

    public function setModifiedBy($modifiedBy)
    {
        $this->lastModifiedBy = $this->getModifiedBy();

        $this->__setter('modifiedBy', $modifiedBy);
    }

    public function getModifiedBy()
    {
        return $this->__getter('modifiedBy');
    }
}
