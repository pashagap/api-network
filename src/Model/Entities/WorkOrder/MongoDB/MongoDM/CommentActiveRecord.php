<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Core\Model\Entities\User\MongoDB\MongoDM\Model as UserModel;

/**
 * Class CommentActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class CommentActiveRecord extends AbstractActiveRecord
{
    public static $attrs
        = [
            'id' => ['type' => 'string'],
            'message' => ['type' => 'string'],

            'createdBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'createdByData' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'embed'
            ],
            'createdDate' => ['type' => 'date'],
            'images' => [
                'model' => 'Hatch\Core\Model\Common\MongoDB\MongoDM\Image\ImageActiveRecord',
                'type' => 'embeds'
            ]
        ];

    public function update(array $cleanData, $isInit = false)
    {
        if(false === $isInit) {
            $this->updateToCurrentDate('createdDate');
            $this->updateToCurrentUser('createdBy');

            $this->setId();
        }

        return parent::update($cleanData, $isInit);
    }

    public function updateToCurrentDate($fieldName)
    {
        $currentDatetime = new \DateTime();
        $currentMongoDate = new \MongoDate($currentDatetime->getTimestamp());
        $this->{$fieldName} = $currentMongoDate;
    }

    public function updateToCurrentUser($fieldName)
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

    public function setImages($images)
    {
        if (is_array($images)) {
            $imageActiveRecords = [];

            foreach ($images as $image) {
                $imageActiveRecord = $this->sysApp['core.model.image.activeRecord'];
                $imageActiveRecord->update($image);
                array_push($imageActiveRecords, $imageActiveRecord);
            }
            $images = $imageActiveRecords;
        }

        $this->__setter('images', $images);
    }

    public function getImages()
    {
        return $this->__getter('images');
    }

    public function setId($id = null)
    {
        if (null === $id) {
            $id = new \MongoId();
        }

        $this->__setter('id', $id);
    }

    public function setCreatedBy($createdBy)
    {
        $this->__setter('createdBy', $createdBy);
        $this->__setter('createdByData', clone $createdBy);
    }
}
