<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Exception\Model\ValidationModelException;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Core\Model\Entities\User\MongoDB\MongoDM\Model as UserModel;
use Hatch\Inspections\Model\Entities\Question\QuestionOutcomeType;
use Purekid\Mongodm\Collection;
use Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord as InspectionActiveRecord;
use Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\ActiveRecord as TemplateVersionActiveRecord;

/**
 * Class ActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class ActiveRecord extends AbstractActiveRecord
{
    protected $primaryModel = 'inspections.workOrder.model';
    public static $collection = 'InspectionsWorkOrder';

    protected $lastModifiedState = null;

    public static $attrs
        = [
            'inspection' => [
                'model' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'question' => [
                'model' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'templateVersion' => [
                'model' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'answer' => [
                'model' => 'Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\ActiveRecord',
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
            'modifiedDate' => ['type' => 'date'],

            'assigned' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'issue' => ['type' => 'string'],

            'location' => [
                'model' => 'Hatch\Core\Model\Entities\Location\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'comments' => [
                'model' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\CommentActiveRecord',
                'type' => 'embeds'
            ],

            'images' => [
                'model' => 'Hatch\Core\Model\Common\MongoDB\MongoDM\Image\ImageActiveRecord',
                'type' => 'embeds'
            ],

            // incomplete, started, completed
            'state' => ['type' => 'string', 'default' => 'incomplete'],
            'stateHistory' => [
                'model' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\StateHistoryActiveRecord',
                'type' => 'embeds'
            ]
        ];

    public function validate()
    {
        if (!$this->answerOutcomeIsRight()) {
            throw new ValidationModelException(
                'Work order',
                'can be created only from failed answer'
            );
        }

        $uniqueCriteria = [
            'answer.$id' => $this->answer->getId()
        ];

        if (!$this->isUnique($uniqueCriteria)) {
            throw new ValidationModelException(
                'Work order',
                sprintf(
                    'in this inspection for answer on question "%s" already exists',
                    $this->question->question
                )
            );
        }

        parent::validate();
    }

    public function setState($state)
    {
        $this->lastModifiedState = $this->getState();
        $this->__setter('state', $state);
    }

    public function getState()
    {
        return $this->__getter('state');
    }

    public function stateChanged()
    {
        return null !== $this->lastModifiedState && $this->lastModifiedState !== $this->getState();
    }

    protected function updateStateHistory()
    {
        $historyItem = $this->sysApp['inspections.workOrder.stateHistory.activeRecord'];
        $historyItem->update(
            [
                'state' => $this->getState(),
                'on' => $this->modifiedDate
            ]
        );

        $this->addToStateHistory($historyItem);
    }

    public function addToStateHistory($historyItem)
    {
        $stateHistory = $this->getStateHistory();
        if (null === $stateHistory) {
            $stateHistory = new Collection();
        }

        $stateHistory->add($historyItem);
        $this->setStateHistory($stateHistory);
    }

    public function getStateHistory()
    {
        return $this->__getter('stateHistory');
    }

    public function setStateHistory($stateHistory)
    {
        $this->__setter('stateHistory', $stateHistory);
    }

    private function answerOutcomeIsRight()
    {
        /** @var QuestionOutcomeType $outcomeType */
        $outcomeType = $this->sysApp['inspections.question.outcomeType'];

        return $outcomeType::FAIL === $this->answer->getOutcome();
    }

    protected function __preUpdate()
    {
        $this->updateToCurrentDate('modifiedDate');
        $this->updateToCurrentUser('modifiedBy');

        if ($this->stateChanged()) {
            $this->updateStateHistory();
        }

        return parent::__preUpdate();
    }

    protected function __preInsert()
    {
        $this->updateToCurrentDate('createdDate');
        $this->updateToCurrentUser('createdBy');

        $this->modifiedDate = $this->createdDate;
        $this->modifiedBy = $this->createdBy;

        $inspection = $this->getInspection();
        $this->setTemplateVersion(
            $inspection->getTemplateVersion()
        );


        $this->updateStateHistory();

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
                    $this->sysApp['inspections.workOrder.comment.activeRecord'];
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

    /**
     * @return InspectionActiveRecord
     */
    public function getInspection()
    {
        return $this->__getter('inspection');
    }

    public function setInspection($inspection)
    {
        $this->__setter('inspection', $inspection);
    }

    /**
     * @param TemplateVersionActiveRecord $templateVersion
     */
    public function setTemplateVersion($templateVersion)
    {
        $this->__setter('templateVersion', $templateVersion);
    }

    /**
     * @return TemplateVersionActiveRecord
     */
    public function getTemplateVersion()
    {
        return $this->__getter('templateVersion');
    }
}
