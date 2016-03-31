<?php

namespace Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Core\Model\Entities\User\MongoDB\MongoDM\Model as UserModel;

class ActiveRecord extends AbstractActiveRecord
{
    protected $lastModifiedBy = null;
    protected $lastModifiedDate = null;

    protected $primaryModel = 'socialNetwork.posts.model';
    public static $collection = 'Posts';

    public static $attrs
        = [
            'text' => ['type' => 'string'],

            'location' => [
                'model' => 'Hatch\Core\Model\Entities\Location\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'createdBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'modifiedBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'createdDate' => ['type' => 'date'],
            'modifiedDate' => ['type' => 'date'],

            'comments' => [
                'model' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\CommentsActiveRecord',
                'type' => 'embeds'
            ]
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

    public function setComments($comments)
    {
        if (is_array($comments)) {
            $commentActiveRecords = [];

            foreach ($comments as $comment) {
                $commentActiveRecord =
                    $this->sysApp['socialNetwork.post.comments.activeRecord'];
                $commentActiveRecord->update($comment);
                array_push($commentActiveRecords, $commentActiveRecord);
            }
            $comments = $commentActiveRecords;
        }

        $this->__setter('comments', $comments);
    }

    public function getComments()
    {
        return $this->__getter('comments');
    }
}